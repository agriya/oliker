'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:CreditCardController
 * @description
 * # CreditCardController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Common.Paypal')
    .controller('CreditCardController', function($rootScope, $filter,SweetAlert, CreditCardFactory, UserFactory, CreditCardListFactory, CreditCardRemoveFactory, flash) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Add Credit Card");
        vm.head_title = $filter("translate")("CreditCard");
        vm.head_description = $filter("translate")("Manage Your CreditCard");
        vm.index = function() {
            vm.active_tab4 = "active";
            vm.credit_card_tab = "active";
            var params = {};
            params.userId = $rootScope.user.id;
            UserFactory.get(params, function(response) {
                vm.user_details = response.data;
                vm.user_available_balance = response.data.available_wallet_amount;
                vm.getCreditCardList(vm.user_details);
                vm.loader = false;
            });
        };
        vm.addCreditCardDetails = function(form) {
            if (angular.isDefined(vm.buyer.credit_card_expired) && (vm.buyer.credit_card_expired.month || vm.buyer.credit_card_expired.year)) {
                vm.credit_card_expire = vm.buyer.credit_card_expired.month + "/" + vm.buyer.credit_card_expired.year;
            }
            var card_expiry_date = vm.credit_card_expire;
            var card_expiry = card_expiry_date.split('/');
            vm.buyer.cvv2 = vm.buyer.credit_card_code;
            vm.buyer.expire_month = card_expiry[0];
            vm.buyer.expire_year = card_expiry[1];
            delete vm.buyer.credit_card_code;
            delete vm.buyer.credit_card_expired;
            CreditCardFactory.create(vm.buyer, function(response) {
                var flashMessage;
                if (response.error.code === 0) {
                    vm.getCreditCardList(vm.user_details);
                    flashMessage = $filter("translate")("Credit Card Deatils Added.");
                    flash.set(flashMessage, 'success', false);
                    vm.buyer = {};
                } else {
                    flashMessage = $filter("translate")(response.error.message);
                    flash.set(flashMessage, 'error', false);
                    vm.buyer = {};
                }
            });
        };
        vm.getCreditCardList = function(user_details) {
            var params = {};
            params.user_id = user_details.id;
            CreditCardListFactory.get(params, function(response) {
                vm.credit_card_details = response.data;
            });
        };
        vm.removeCardDetails = function(cc_id) {
              SweetAlert.swal({
                title: $filter("translate")("Are you sure you want to delete?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0e6cfb",
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                animation:false,
            }, function(isConfirm) {
                if (isConfirm) {
            var params = {};
            params.vaultId = cc_id;
            CreditCardRemoveFactory.remove(params, function(response) {
                vm.getCreditCardList(vm.user_details);
                var flashMessage;
                if (response.error.code === 0) {
                    flashMessage = $filter("translate")("Credit Card Deatils Removed.");
                    flash.set(flashMessage, 'success', false);
                } else {
                    flashMessage = $filter("translate")(response.error.message);
                    flash.set(flashMessage, 'error', false);
                }
            });
                }
            });
        };
        vm.index();
    });