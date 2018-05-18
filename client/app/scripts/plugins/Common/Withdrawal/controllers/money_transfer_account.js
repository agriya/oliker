'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:MoneyTransferAccountController
 * @description
 * # MoneyTransferAccountController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('MoneyTransferAccountController', function($rootScope, MoneyTransferAccountFactory,SweetAlert, flash, $filter, $state) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Money Transfer Accounts");
        vm.head_title = $filter("translate")("Money Transfer Account");
        vm.head_description = $filter("translate")("Manage All Your Money Transfers");
        var params = {};
        params.user_id = $rootScope.user.id;
        vm.index = function() {
            vm.active_tab4 = "active";
            vm.money_transtab = "active";
            vm.loader = true;
            MoneyTransferAccountFactory.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.moneyTransferAccLists = response.data;
                }
                vm.loader = false;
            });
        };
        vm.MoneyTransferAccSubmit = function($valid) {
            if ($valid) {
                params.account = vm.account;
                params.is_primary = true;
                MoneyTransferAccountFactory.save(params, function(response) {
                    vm.response = response;
                    $state.reload();
                    flash.set($filter("translate")("Account Added successfully"), 'success', true);
                }, function() {
                    flash.set($filter("translate")("Account could not be added"), 'error', false);
                });
            }
        };
        vm.MoneyTransferAccDelete = function(id) {
             SweetAlert.swal({
                title: $filter("translate")("Are you sure you want to delete the Account?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0e6cfb",
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                animation:false,
            }, function(isConfirm) {
                if (isConfirm) {
            var param = {};
            param.user_id = $rootScope.user.id;
            param.account_id = id;
            MoneyTransferAccountFactory.delete(param, function(response) {
                vm.response = response;
                if (vm.response.error.code === 0) {
                    $state.reload();
                    flash.set($filter("translate")("Account deleted successfully."), 'success', false);
                } else {
                    flash.set($filter("translate")("Account could not be deleted."), 'error', false);
                }
            });
                }
            });
        };
        vm.index();
    });