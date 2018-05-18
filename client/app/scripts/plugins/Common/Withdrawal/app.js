/*globals $:false */
'use strict';
/**
 * @ngdoc overview
 * @name olikerApp
 * @description
 * # olikerApp
 *
 * Main module of the application.
 */
angular.module('olikerApp.Common.Withdrawal', [])
    .config(function($stateProvider, $urlRouterProvider) {
        var getToken = {
            'TokenServiceData': function(TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
        };
        $urlRouterProvider.otherwise('/');
        $stateProvider.state('user_cash_withdrawals', {
                url: '/cash_withdrawals',
                templateUrl: 'scripts/plugins/Common/Withdrawal/views/default/cash_withdrawals.html',
                resolve: getToken
            })
            .state('money_transfer_account', {
                url: '/money_transfer_accounts',
                templateUrl: 'scripts/plugins/Common/Withdrawal/views/default/money_transfer_account.html',
                resolve: getToken
            });
    });