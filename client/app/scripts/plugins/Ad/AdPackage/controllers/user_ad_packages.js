'use strict';
/**
 * @ngdoc function
 * @name olikerApp.AdPackagesController
 * @description
 * # AdPackagesController
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad.AdPackage')
    .controller('AdPackagesController', function($rootScope, $stateParams, $location, UserFactory, $filter, $state, $timeout, md5, AdPackagesFactory, CategoriesFactory, CategoryFactory, AdSubscriptionPackagesFactory, PaymentGatewayFactory, CountriesFactory, CreditCardListFactory, WalletFactory, $window, flash) {
        var vm = this;
        vm.zazpay = true;
        vm.paypal = false;
        vm.credit_card_buyer = true;
        vm.paypal_buyer = true;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("AdPackages");
        vm.head_title = $filter("translate")("Packages");
        vm.head_description = $filter("translate")("Choose Subscription Packages");
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
            flash.set(flashMessage, 'error', false);
            $state.go('user_ad_packages');
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Payment successfully completed.");
            flash.set(flashMessage, 'success', false);
            $state.go('ad_subscription_packages');
        }
        vm.is_paynow = false;
        vm.is_show_ad_package = false;
        vm.ad_user_package = {};
        vm.buyer = {};
        vm.paynow_is_disabled = false;
        vm.payment_note_enabled = false;
        vm.payer_form_enabled = true;
        vm.is_wallet_page = true;
        vm.save_btn = false;
        vm.is_show_pay = false;
        vm.ad_package_default = true;
        vm.index = function() {
            vm.active_tab6 = "active";
            vm.package_tab = "active";
            vm.loader = true;
            vm.getCategories();
            vm.getPaymentList();
            vm.getCountries();
            if ($stateParams.category_id) {
                var category_id = $stateParams.category_id;
                vm.getAdPackage(category_id);
                vm.selected_category = {};
                CategoryFactory.get({
                    categoryId: category_id
                }, function(response) {
                    vm.selected_category = {
                        id: response.data.id,
                        name: response.data.name
                    };
                });
            }
            var params = {};
            params.userId = $rootScope.user.id;
            UserFactory.get(params, function(response) {
                vm.user_details = response.data;
                vm.user_available_balance = response.data.available_wallet_amount;
                vm.loader = false;
                vm.getCreditCardList(vm.user_details);
            });
        };
        vm.refreshAddresses = function(keyword) {
            var params = {};
            params.q = keyword;
            return CategoriesFactory.get(params, function(response) {
                vm.categories = response.data;
            });
        };
        vm.getCategories = function() {
            CategoriesFactory.get(function(response) {
                vm.categories = response.data;
            });
        };
        vm.getAdPackage = function(category) {
            var params = {};
            vm.ad_packages = [];
            vm.is_show_ad_package = true;
            params.category_id = category;
            AdPackagesFactory.get(params, function(response) {
                angular.forEach(response.data, function(ad_package) {
                    if (ad_package.user_ad_package === null) {
                        vm.ad_packages.push(ad_package);
                    }
                });
            });
        };
        vm.selectAdPackage = function(ad_package, index) {
            vm.is_show_pay = true;
            vm.selected_package = ad_package;
            vm.selected_index = index;
            vm.ad_package_default = false;
            if (vm.selected_index === 0) {
                vm.ad_package_default = true;
            }
        };
        vm.getPaymentList = function() {
            var payment_gateways = [];
            PaymentGatewayFactory.get(function(payment_response) {
                vm.group_gateway_id = "";
                if (payment_response.error.code === 0) {
                    if (payment_response.wallet) {
                        vm.wallet_enabled = true;
                    }
                    angular.forEach(payment_response.zazpay.gateways, function(gateway_group_value, gateway_group_key) {
                        if (gateway_group_key === 0) {
                            vm.group_gateway_id = gateway_group_value.id;
                            vm.first_gateway_id = gateway_group_value.id;
                        }
                        //jshint unused:false
                        angular.forEach(gateway_group_value.gateways, function(payment_geteway_value, payment_geteway_key) {
                            var payment_gateway = {};
                            var suffix = 'sp_';
                            if (gateway_group_key === 0) {
                                vm.sel_payment_gateway = 'sp_' + payment_geteway_value.id;
                            }
                            suffix += payment_geteway_value.id;
                            payment_gateway.id = payment_geteway_value.id;
                            payment_gateway.payment_id = suffix;
                            payment_gateway.group_id = gateway_group_value.id;
                            payment_gateway.display_name = payment_geteway_value.display_name;
                            payment_gateway.thumb_url = payment_geteway_value.thumb_url;
                            payment_gateway.suffix = payment_geteway_value._form_fields._extends_tpl.join();
                            payment_gateway.form_fields = payment_geteway_value._form_fields._extends_tpl.join();
                            payment_gateway.instruction_for_manual = payment_geteway_value.instruction_for_manual;
                            payment_gateways.push(payment_gateway);
                        });
                    });
                    vm.gateway_groups = payment_response.zazpay.gateways;
                    vm.payment_gateways = payment_gateways;
                    vm.form_fields_tpls = payment_response.zazpay._form_fields_tpls;
                    vm.show_form = [];
                    vm.form_fields = [];
                    angular.forEach(vm.form_fields_tpls, function(key, value) {
                        if (value === 'buyer') {
                            vm.form_fields[value] = 'scripts/plugins/Common/ZazPay/views/default/buyer.html';
                        }
                        if (value === 'credit_card') {
                            vm.form_fields[value] = 'scripts/plugins/Common/ZazPay/views/default/credit_card.html';
                        }
                        if (value === 'manual') {
                            vm.form_fields[value] = 'scripts/plugins/Common/ZazPay/views/default/manual.html';
                        }
                        vm.show_form[value] = true;
                    });
                    vm.gateway_id = 1;
                }
            });
        };
        vm.paymentGatewayUpdate = function(payment) {
            if (payment === 'Manual / Offline') {
                vm.payment_note_enabled = true;
            }
            var keepGoing = true;
            vm.buyer = {};
            angular.forEach(vm.form_fields_tpls, function(key, value) {
                vm.show_form[value] = false;
            });
            vm.gateway_id = 1;
            angular.forEach(vm.gateway_groups, function(res) {
                if (res.display_name === payment && payment !== 'Wallet') {
                    var selPayment = '';
                    angular.forEach(vm.payment_gateways, function(response) {
                        if (keepGoing) {
                            if (response.group_id === res.id) {
                                selPayment = response;
                                keepGoing = false;
                                vm.paymentFormUpdate(selPayment.id, selPayment.form_fields);
                            }
                        }
                    });
                    vm.sel_payment_gateway = "sp_" + selPayment.id;
                    vm.group_gateway_id = selPayment.group_id;
                }
            });
            if (payment === 'Wallet') {
                vm.gateway_id = 2;
            }
        };
        vm.paymentFormUpdate = function(res, res1) {
            vm.paynow_is_disabled = false;
            vm.sel_payment_gateway = "sp_" + res;
            vm.array = res1.split(',');
            angular.forEach(vm.array, function(value) {
                vm.show_form[value] = true;
            });
        };
        vm.PaymentSubmit = function(form) {
            var payment_id = '';
            vm.buyer.payment_gateway_id = '';
            vm.buyer.gateway_id = '';
            if (vm.paypal === true) {
                vm.gateway_id = 4;
            }
            if (vm.sel_payment_gateway && vm.gateway_id === 1) {
                payment_id = vm.sel_payment_gateway.split('_')[1];
            }
            if (angular.isDefined(vm.buyer.credit_card_expired) && (vm.buyer.credit_card_expired.month || vm.buyer.credit_card_expired.year)) {
                vm.buyer.credit_card_expire = vm.buyer.credit_card_expired.month + "/" + vm.buyer.credit_card_expired.year;
            }
            vm.buyer.payment_gateway_id = vm.gateway_id;
            vm.buyer.gateway_id = payment_id;
            if (angular.isDefined(vm.selected_package)) {
                vm.buyer.ad_package_id = vm.selected_package.id;
            } else {
                vm.buyer.ad_package_id = vm.ad_packages[0].id;
            }
            if (vm.paypal === true) {
                if (vm.payment_type === 'credit_card') {
                    var card_expiry_date = vm.buyer.credit_card_expire;
                    var card_expiry = card_expiry_date.split('/');
                    vm.buyer.cvv2 = vm.buyer.credit_card_code;
                    vm.buyer.expire_month = card_expiry[0];
                    vm.buyer.expire_year = card_expiry[1];
                    delete vm.buyer.credit_card_expire;
                    delete vm.buyer.credit_card_code;
                    delete vm.buyer.credit_card_name_on_card;
                    delete vm.buyer.credit_card_expired;
                }
            }
            if (form.$valid) {
                vm.paynow_is_disabled = true;
                var flashMessage;
                if (vm.total > vm.user_available_balance && vm.gateway_id === 2) {
                    flashMessage = $filter("translate")("Your wallet has insufficient money.");
                    flash.set(flashMessage, 'error', false);
                    vm.paynow_is_disabled = false;
                    return true;
                }
                AdSubscriptionPackagesFactory.create(vm.buyer, function(response) {
                    if (response.error.code === 0) {
                        if (angular.isDefined(response.redirect_url)) {
                            $window.location.href = response.redirect_url;
                        } else if (response.is_payment_completed === true) {
                            flashMessage = $filter("translate")("Your payment complted.");
                            flash.set(flashMessage, 'error', false);
                            $state.go('ad_subscription_packages');
                        } else if (response.payment_response.status === 'Captured') {
                            flashMessage = $filter("translate")("Your Packaged added successfully.");
                            flash.set(flashMessage, 'success', false);
                            $state.go('ad_subscription_packages');
                        } else if (response.error.code === 0) {
                            flashMessage = $filter("translate")("Payment successfully completed.");
                            flash.set(flashMessage, 'success', false);
                            $state.go('ad_subscription_packages');
                        } else if (response.error.code === 512) {
                            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
                            flash.set(flashMessage, 'error', false);
                            $state.go('ad_subscription_packages');
                        }
                    } else {
                        flashMessage = $filter("translate")("We are unable to place your order. Please try again.");
                        flash.set(flashMessage, 'error', false);
                    }
                    vm.paynow_is_disabled = false;
                });
            }
        };
        vm.getCountries = function() {
            CountriesFactory.get({
                limit: 'all'
            }, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.countries = response.data;
                }
            });
        };
        vm.zazpayment = function() {
            vm.zazpay = true;
            vm.paypal = false;
            vm.credit_card_buyer = true;
            vm.payment_note_enabled = false;
            vm.index();
        };
        vm.paypalpayment = function() {
            vm.paypal = true;
            vm.zazpay = false;
            vm.payment_type = 'credit_card';
            vm.payment_note_enabled = false;
            vm.paypal_buyer = true;
        };
        vm.paymentChange = function(type) {
            if (type === 'credit_card') {
                vm.payment_type = type;
                vm.credit_card_buyer = true;
            } else if (type === 'paypal') {
                vm.payment_type = type;
                vm.credit_card_buyer = false;
                vm.paypal_buyer = true;
            } else if (type === 'wallet') {
                vm.credit_card_buyer = false;
                vm.paypal_buyer = false;
                vm.payment_type = type;
                vm.buyer.vault_id = vm.credit_card_details[0].id;
            }
        }
        vm.getCreditCardList = function(user_details) {
            var params = {};
            params.user_id = user_details.id;
            CreditCardListFactory.get(params, function(response) {
                vm.credit_card_details = response.data;
            });
        };
        vm.index();
    });