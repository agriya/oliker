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
angular.module('olikerApp.Common.Paypal', [])
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
        $stateProvider.state('credit_card', {
            url: '/add/credit_card',
            templateUrl: 'scripts/plugins/Common/Paypal/views/default/credit_card_details.html',
            resolve: getToken
        });
    });