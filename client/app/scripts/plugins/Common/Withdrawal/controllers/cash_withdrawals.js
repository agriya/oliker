'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:CashWithdrawalsController
 * @description
 * # CashWithdrawalsController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Common.Withdrawal')
    .controller('CashWithdrawalsController', function($rootScope, cashWithdrawalsFactory, MoneyTransferAccountFactory, flash, $filter, $state) {
        /*jshint -W117 */
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Cash Withdrawals");
        vm.head_title = $filter("translate")("Cash Withdrawals");
        vm.head_description = $filter("translate")("Manage All Your Cash Withdrawals");
        vm.minimum_withdraw_amount = $rootScope.settings.USER_MINIMUM_WITHDRAW_AMOUNT;
        vm.maximum_withdraw_amount = $rootScope.settings.USER_MAXIMUM_WITHDRAW_AMOUNT;
        vm.user_available_balance = $rootScope.user.available_wallet_amount;
        vm.account_error = false;
        vm.index = function() {
            var params = {};
            params.user_id = $rootScope.user.id;
            vm.active_tab4 = "active";
            vm.cash_tab = "active";
            vm.loader = true;
            cashWithdrawalsFactory.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.cashWithdrawalsList = response.data;
                }
                vm.loader = false;
            });
            params.filter = 'active';
            MoneyTransferAccountFactory.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.moneyTransferList = response.data;
                }
            });
        };
        vm.selectedAccount = function(id) {
            vm.account_id = id;
            vm.account_error = false;
        };
        vm.userCashWithdrawSubmit = function($valid) {
            var params = {};
            params.user_id = $rootScope.user.id;
            if (angular.isUndefined(vm.account_id)) {
                vm.account_error = true;
            } else {
                vm.account_error = false;
            }
            if ($valid && vm.account_error === false) {
                vm.amount = angular.element('#amount')
                    .val();
                if (parseFloat(vm.user_available_balance) > parseFloat(vm.amount)) {
                    params.amount = vm.amount;
                    params.money_transfer_account_id = vm.account_id;
                    params.remark = "";
                    cashWithdrawalsFactory.save(params, function(response) {
                        if (response.error.code === 0) {
                            $state.reload();
                            flash.set($filter("translate")("Your request submitted successfully."), 'success', true);
                        } else {
                            flash.set($filter("translate")("Withdraw request could not be added"), 'error', false);
                        }
                    }, function() {
                        flash.set($filter("translate")("Withdraw request could not be added"), 'error', false);
                    });
                } else {
                    flash.set("You Dont have sufficient amount in your wallet.", "error", false);
                }
            }
        };
        vm.index();
    });