'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:AdPaymentController
 * @description
 * # AdPaymentController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad.AdExtra')
    .controller('AdPaymentController', function($rootScope, $filter, $state, $stateParams, UserFactory, AdFactory, AdExtraDaysFactory, CountriesFactory, PaymentGatewayFactory, CreditCardListFactory, flash, $window) {
        var vm = this;
        vm.zazpay = true;
        vm.paypal = false;
        vm.credit_card_buyer = true;
        vm.paypal_buyer = true;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("ExtraDay");
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
            flash.set(flashMessage, 'error', false);
            $state.go('my_ads');
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Payment successfully completed.");
            flash.set(flashMessage, 'success', false);
            $state.go('my_ads');
        }
        vm.extra_day = {};
        vm.ad = {};
        vm.buyer = {};
        vm.paynow_is_disabled = false;
        vm.payment_note_enabled = false;
        vm.payer_form_enabled = true;
        vm.payer_form_enabled = true;
        vm.is_wallet_page = true;
        vm.input = [];
        vm.index = function() {
            vm.getCountries();
            vm.getAdDetails();
            vm.loader = true;
            var params = {};
            params.userId = $rootScope.user.id;
            UserFactory.get(params, function(response) {
                vm.user_details = response.data;
                vm.user_available_balance = response.data.available_wallet_amount;
                vm.loader = false;
                vm.getCreditCardList(vm.user_details);
            });
            var payment_gateways = [];
            PaymentGatewayFactory.get({}, function(payment_response) {
                if (vm.zazpay === true) {
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
                }
            });
        };
        //Payement pane changing function 
        vm.paymentGatewayUpdate = function(pane) {
            if (pane === 'Manual / Offline') {
                vm.payment_note_enabled = true;
            }
            var keepGoing = true;
            vm.buyer = {};
            angular.forEach(vm.form_fields_tpls, function(key, value) {
                vm.show_form[value] = false;
            });
            vm.gateway_id = 1;
            angular.forEach(vm.gateway_groups, function(res) {
                if (res.display_name === pane && pane !== 'Wallet') {
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
            if (pane === 'Wallet') {
                vm.gateway_id = 2;
            }
        };
        //Pay now button diabling option 
        vm.paymentFormUpdate = function(res, res1) {
            vm.paynow_is_disabled = false;
            vm.sel_payment_gateway = "sp_" + res;
            vm.array = res1.split(',');
            angular.forEach(vm.array, function(value) {
                vm.show_form[value] = true;
            });
        };
        //Getting countries
        vm.getCountries = function() {
            CountriesFactory.get({
                limit: 'all'
            }, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.countries = response.data;
                }
            });
        };
        //Extra-day key Spliting  
        vm.getChecked = function(obj) {
            var checked = [];
            for (var key in obj) {
                if (obj[key]) {
                    checked.push({
                        id: key
                    });
                }
            }
            return checked;
        };
        //Extra Day Create
        vm.extraDaySave = function(form) {
            if (vm.paypal === true) {
                vm.gateway_id = 4;
            }
            var flashMessage;
            var payment_id = '';
            if (vm.sel_payment_gateway && vm.gateway_id === 1) {
                payment_id = vm.sel_payment_gateway.split('_')[1];
            }
            vm.buyer.ad_extra_id = vm.getChecked(vm.extra_day);
            // vm.ad.user_id = $rootScope.user.id;
            vm.buyer.payment_gateway_id = vm.gateway_id;
            vm.buyer.gateway_id = payment_id;
            vm.buyer.category_id = vm.ad_detail.category_id;
            if (angular.isDefined(vm.buyer.credit_card_expired) && (vm.buyer.credit_card_expired.month || vm.buyer.credit_card_expired.year)) {
                vm.buyer.credit_card_expire = vm.buyer.credit_card_expired.month + "/" + vm.buyer.credit_card_expired.year;
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
            if (form) {
                vm.paynow_is_disabled = true;
                AdFactory.update({
                    adId: $stateParams.id
                }, vm.buyer, function(response) {
                    if (angular.isDefined(response.payment_response)) {
                        if (angular.isDefined(response.redirect_url)) {
                            $window.location.href = response.redirect_url;
                        } else if (response.payment_response.status === 'Captured') {
                            flashMessage = $filter("translate")("Ad Extra days Added.payment completed");
                            flash.set(flashMessage, 'success', false);
                            vm.paynow_is_disabled = false;
                            $state.go('my_ads');
                        } else if (response.payment_response.status === 'Pending') {
                            flashMessage = $filter("translate")("Ad Extra Days Added. Payment in progress");
                            flash.set(flashMessage, 'success', false);
                            vm.paynow_is_disabled = false;
                            $state.go('my_ads');
                        }
                    } else if (response.error.code === 0) {
                        flashMessage = $filter("translate")("Ad Extra Days Added");
                        flash.set(flashMessage, 'success', false);
                        vm.paynow_is_disabled = false;
                        $state.go('my_ads');
                    }
                }, function() {
                    flashMessage = $filter("translate")("Ad Extra Days Not able To Added");
                    flash.set(flashMessage, 'error', false);
                    vm.paynow_is_disabled = false;
                });
            }
        };
        vm.getAdDetails = function() {
            AdFactory.get({
                adId: $stateParams.id
            }, function(response) {
                vm.ad_detail = response.data;
                vm.getAdExtraDays(vm.ad_detail);
            });
        };
        vm.getAdExtraDays = function(ad_detail) {
            var params = {};
            params.category_id = ad_detail.category_id;
            AdExtraDaysFactory.get(params, function(response) {
                if (response.error.code === 0) {
                    if (response.data.length > 0) {
                        vm.category_extrdays = response.data;
                    }
                }
                vm.loader = false;
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