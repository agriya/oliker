'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:TransactionsController
 * @description
 * # TransactionsController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('TransactionsController', function($rootScope, TransactionFactory, flash, $filter) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Transactions");
        var params = {};
        vm.head_title = $filter("translate")("Transactions");
        vm.head_description = $filter("translate")("Manage All Your Transactions");
        params.user_id = $rootScope.user.id;
        vm.index = function() {
            vm.active_tab4 = "active";
            vm.trans_tab = "active";
            vm.loader = true;
            TransactionFactory.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    vm.transactionsList = response.data;
                }
                vm.loader = false;
            });
        };
        vm.index();
    });