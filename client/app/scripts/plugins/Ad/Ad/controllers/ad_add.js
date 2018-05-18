'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:AdController
 * @description
 * # AdController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('AdController', function($rootScope, AdsFactory, $filter, CategoryAdCountCheckFactory, CreditCardListFactory, $state, AdsAdvertiserTypeFactory, AdFavoritesFactory, CategoriesFactory, md5, $uibModal, $log, $builder, $validator, Upload, AdExtraDaysFactory, CountriesFactory, PaymentGatewayFactory, UserFactory, flash, $window, $stateParams, $uibModalStack, CategoryFactory) {
        var vm = this;
        vm.zazpay = true;
        vm.paypal = false;
        vm.credit_card_buyer = true;
        vm.paypal_buyer = true;
        vm.free_ad_btn = false;
        vm.categgory_select_btn = true;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Ads Create");
        var flashMessage;
        if (parseInt($stateParams.error_code) === 512) {
            flashMessage = $filter("translate")("Payment Failed. Please, try again.");
            flash.set(flashMessage, 'error', false);
            $state.go('ad');
        } else if (parseInt($stateParams.error_code) === 0) {
            flashMessage = $filter("translate")("Payment successfully completed.");
            flash.set(flashMessage, 'success', false);
            $state.go('my_ads');
        }
        if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
            vm.form = [];
            var p = $builder.forms['default'].length;
            for (var q = 0; q < p; q++) {
                $builder.removeFormObject('default', q);
            }
            $builder.removeFormObject('default');
        }
        vm.buyer = {};
        vm.category_chosen = '';
        vm.paynow_is_disabled = false;
        vm.payment_note_enabled = false;
        vm.payer_form_enabled = true;
        vm.is_wallet_page = true;
        vm.save_btn = false;
        vm.first_gateway_id = "";
        vm.ad = {};
        vm.is_show_pay = false;
        vm.is_show_extrday = false;
        vm.place = null;
        vm.extra_day = {};
        vm.ad.image = [];
        vm.ad.ad_form_field = [];
        vm.ad_image_files = [];
        if (angular.isDefined($stateParams.category_id) && $stateParams.category_id !== "") {
            var params = {};
            params.categoryId = $stateParams.category_id;
            CategoryFactory.get(params, function(response) {
                vm.category_chosen = response.data;
                vm.selectCategory(vm.category_chosen);
            });
        }
        vm.autocompleteOptions = {
            types: ['cities']
        };
        //init function 
        vm.index = function() {
            vm.getCountries();
            vm.getAdsAdvertiser();
            vm.getCategories();
            vm.getUser();
            vm.getpayments();
        };
        //Getting AdsAdvertiser Type
        vm.getAdsAdvertiser = function() {
            AdsAdvertiserTypeFactory.get(function(response) {
                vm.advertiser_types = response.data;
            });
        };
        vm.getUser = function() {
            var params = {};
            params.userId = $rootScope.user.id;
            UserFactory.get(params, function(response) {
                vm.user_details = response.data;
                vm.user_available_balance = response.data.available_wallet_amount;
                vm.user_available_points = response.data.available_points;
                vm.getCreditCardList(vm.user_details);
            });
        };
        //Getting Categories 
        vm.getCategories = function() {
            vm.group_categories = [];
            var params = {};
            params.sortby = "ASC";
            params.parent_id = 0;
            params.limit = 'all';
            params.sort = 'name';
            CategoriesFactory.get(params, function(response) {
                vm.categories = response.data;
                vm.category_metadata = response._metadata;
                vm.max = vm.category_metadata.max_level;
                vm.level = Math.ceil(12 / vm.category_metadata.max_level);
                for (var i = 0; i <= vm.max; i++) {
                    if (i === 0) {
                        vm.group_categories[i] = $filter('filter')(vm.categories, {
                            parent_id: 0
                        });
                    } else {
                        vm.group_categories[i] = [];
                    }
                }
            });
        };
        //Selecting the category and category form
        vm.selectCategory = function(category) {
            vm.is_show_extrday = false;
            vm.categgory_select_btn = false;
            if (angular.isDefined(category) && category !== null) {
                vm.Category_extrday(category.id);
                vm.ad.category_id = category.id;
            }
            vm.category = category;
            CategoryAdCountCheckFactory.get({
                categoryId: vm.category.id
            }, function(response) {
                vm.amount_details = response.data;
                if (response.data.amount > 0 && response.data.payment_status === 1) {
                    vm.ad_fee = response.data.amount;
                    vm.free_ad_btn = false;
                    vm.is_show_pay = true;
                    vm.getpayments();
                } else if (response.data.amount === 0) {
                    vm.is_show_pay = false;
                    vm.free_ad_btn = true;
                }
            });
            vm.ad_form_fields = category.form_field;
            if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
                vm.form = [];
                var c = $builder.forms['default'].length;
                for (var i = 0; i < c; i++) {
                    $builder.removeFormObject('default', i);
                }
                $builder.removeFormObject('default');
            }
            if (angular.isDefined(vm.ad_form_fields) && vm.ad_form_fields !== null) {
                if (vm.ad_form_fields.length > 0) {
                    angular.forEach(vm.ad_form_fields, function(fields) {
                        $builder.addFormObject('default', {
                            id: fields.id,
                            component: fields.input_types.name,
                            label: fields.label,
                            description: fields.info,
                            placeholder: '',
                            required: fields.is_required,
                            editable: false
                        });
                    });
                    vm.form = $builder.forms['default'];
                }
            }
        };
        //Extra-day gettting function
        vm.Category_extrday = function(category_id) {
            var params = {};
            params.category_id = category_id;
            AdExtraDaysFactory.get(params, function(response) {
                if (response.error.code === 0) {
                    if (response.data.length > 0) {
                        vm.is_show_extrday = true;
                        vm.category_extrdays = response.data;
                    }
                }
            });
        };
        //Extra-day save and payment function 
        vm.extraDaySave = function() {
            vm.ad.ad_extra_id = vm.getChecked(vm.extra_day);
            if (vm.amount_details.amount === 0 && vm.ad.ad_extra_id.length !== 0) {
                vm.extra_day_btn = true;
            }
            if (vm.amount_details.amount === 0 && vm.ad.ad_extra_id.length == 0) {
                vm.extra_day_btn = false;
                vm.free_ad_btn === true;
            }
        };
        vm.getpayments = function() {
            var payment_gateways = [];
            PaymentGatewayFactory.get({}, function(payment_response) {
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
        vm.uploadFiles = function(files) {
            angular.forEach(files, function(image) {
                vm.ad_image_files.push(image);
            });
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    vm.uploadAdAttachment(i, files);
                }
            }
        };
        vm.uploadAdAttachment = function(i, files) {
            Upload.upload({
                    url: '/api/v1/attachments',
                    data: {
                        file: files[i]
                    },
                    class: "Ad"
                })
                .then(function(response) {
                    vm.ad.image.push(response.data.id);
                });
        };
        vm.removeImage = function(index) {
            vm.ad.image.splice(index, 1);
            vm.ad_image_files.splice(index, 1);
        };
        //Create Ad function 
        vm.adsSave = function(AddAdForm) {
            if (angular.isDefined(vm.ad.category_id) && vm.ad.category_id !== null) {
                var flashMessage;
                angular.forEach(vm.input, function(fields) {
                    angular.forEach(vm.ad_form_fields, function(form_field) {
                        if (form_field.id === fields.id) {
                            var name = form_field.name;
                            var obj = {};
                            obj[name] = fields.value;
                            vm.ad.ad_form_field.push(obj);
                        }
                    });
                });
                vm.ad.is_send_email_when_user_contact = (vm.ad.is_send_email_when_user_contact === true) ? 1 : 0;
                vm.ad.is_negotiable = (vm.ad.is_negotiable === true) ? 1 : 0;
                vm.ad.is_an_exchange_item = (vm.ad.is_an_exchange_item === true) ? 1 : 0;
                if (!angular.isString(vm.place) && vm.place !== null) {
                    vm.ad.latitude = vm.place.geometry.location.lat();
                    vm.ad.longitude = vm.place.geometry.location.lng();
                    vm.ad.location = vm.place.formatted_address;
                    angular.forEach(vm.place.address_components, function(value) {
                        if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                            vm.ad.city_name = value.long_name;
                        }
                        if (value.types[0] === 'administrative_area_level_1') {
                            vm.ad.state_name = value.long_name;
                        }
                        if (value.types[0] === 'country') {
                            vm.ad.country_iso2 = value.short_name;
                        }
                    });
                }
                vm.ad.is_active = 1;
                var payment_id = '';
                if (vm.sel_payment_gateway && vm.gateway_id === 1) {
                    payment_id = vm.sel_payment_gateway.split('_')[1];
                }
                if (vm.paypal === true) {
                    vm.gateway_id = 4;
                }
                vm.ad.payment_gateway_id = vm.gateway_id;
                vm.ad.gateway_id = payment_id;
                if (angular.isDefined(vm.buyer.credit_card_expired) && (vm.buyer.credit_card_expired.month || vm.buyer.credit_card_expired.year)) {
                    vm.ad.credit_card_expire = vm.buyer.credit_card_expired.month + "/" + vm.buyer.credit_card_expired.year;
                }
                if (angular.isDefined(vm.buyer.payment_note)) {
                    vm.ad.payment_note = vm.buyer.payment_note;
                }
                if (angular.isDefined(vm.ad_fee)) {
                    vm.ad.ad_fee = vm.ad_fee;
                }
                vm.ad.buyer_name = vm.buyer.buyer_name;
                vm.ad.buyer_email = vm.buyer.buyer_email;
                vm.ad.buyer_address = vm.buyer.buyer_address;
                vm.ad.buyer_city = vm.buyer.buyer_city;
                vm.ad.buyer_state = vm.buyer.buyer_state;
                vm.ad.buyer_country_iso2 = vm.buyer.buyer_country_iso2;
                vm.ad.buyer_phone = vm.buyer.buyer_phone;
                vm.ad.buyer_zipcode = vm.buyer.buyer_zipcode;
                vm.ad.credit_card_code = vm.buyer.credit_card_code;
                vm.ad.credit_card_name_on_card = vm.buyer.credit_card_name_on_card;
                vm.ad.credit_card_number = vm.buyer.credit_card_number;
                if (vm.paypal === true) {
                    if (vm.payment_type === 'credit_card') {
                        vm.ad.first_name = vm.buyer.first_name;
                        vm.ad.last_name = vm.buyer.last_name;
                        var card_expiry_date = vm.ad.credit_card_expire;
                        var card_expiry = card_expiry_date.split('/');
                        vm.ad.cvv2 = vm.buyer.credit_card_code;
                        vm.ad.expire_month = card_expiry[0];
                        vm.ad.expire_year = card_expiry[1];
                        vm.ad.credit_card_type = vm.buyer.credit_card_type;
                        vm.ad.gateway_id = '';
                        delete vm.ad.credit_card_expire;
                        delete vm.ad.credit_card_code;
                        // delete vm.ad.credit_card_name_on_card;
                        delete vm.ad.credit_card_expire;
                    }
                    if (vm.payment_type === 'paypal') {
                        vm.ad.first_name = vm.buyer.first_name;
                        vm.ad.last_name = vm.buyer.last_name;
                        delete vm.ad.gateway_id;
                    }
                }
                if (AddAdForm) {
                    vm.paynow_is_disabled = true;
                    AdsFactory.create(vm.ad, function(response) {
                        if (angular.isDefined(response.payment_response)) {
                            if (angular.isDefined(response.redirect_url)) {
                                $window.location.href = response.redirect_url;
                            } else if (response.payment_response.status === 'Captured') {
                                flashMessage = $filter("translate")("Your Ad created successfully.");
                                flash.set(flashMessage, 'success', false);
                                if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
                                    vm.form = [];
                                    var d = $builder.forms['default'].length;
                                    for (var a = 0; a < d; a++) {
                                        $builder.removeFormObject('default', a);
                                    }
                                    $builder.removeFormObject('default');
                                }
                                $state.go('my_ads');
                            } else if (response.payment_response.status === 'Pending') {
                                flashMessage = $filter("translate")("Ad Extra Days Added. Payment in progress");
                                flash.set(flashMessage, 'success', false);
                                if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
                                    vm.form = [];
                                    var e = $builder.forms['default'].length;
                                    for (var b = 0; b < e; b++) {
                                        $builder.removeFormObject('default', b);
                                    }
                                    $builder.removeFormObject('default');
                                }
                                vm.paynow_is_disabled = false;
                                $state.go('my_ads');
                            }
                        } else if (response.error.code === 0) {
                            flashMessage = $filter("translate")("Ad Created Successfully");
                            flash.set(flashMessage, 'success', false);
                            if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
                                vm.form = [];
                                var f = $builder.forms['default'].length;
                                for (var k = 0; k < f; k++) {
                                    $builder.removeFormObject('default', k);
                                }
                                $builder.removeFormObject('default');
                            }
                            $state.go('my_ads');
                        }
                        vm.paynow_is_disabled = false;
                    }, function(error) {
                        flashMessage = $filter("translate")("Ad Could not be created.Please Subscribe The Package");
                        flash.set(flashMessage, 'error', false);
                    });
                }
            } else {
                vm.category_error = $filter("translate")("Required");
            }
        };
        var unregisterUpdateCategory = $rootScope.$on('updateCategory', function(event, args) {
            vm.category_chosen = args.category;
            vm.selectCategory(vm.category_chosen);
        });
        $rootScope.$on("$destroy", function() {
            unregisterUpdateCategory();
        });
        vm.open = function() {
            vm.modalInstance = $uibModal.open({
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/category_model.html',
                controller: 'CategoryModalInstaceController as vm',
                animation: false,
                size: 'lg',
                backdrop: 'static',
                resolve: {
                    categories: function() {
                        return vm.group_categories;
                    },
                    level: function() {
                        return vm.level;
                    },
                    max: function() {
                        return vm.max;
                    }
                }
            });
        };
        vm.zazpayment = function() {
            vm.zazpay = true;
            vm.paypal = false;
            vm.getpayments();
            vm.credit_card_buyer = true;
            vm.payment_note_enabled = false;
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
                vm.ad.vault_id = vm.credit_card_details[0].id;
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