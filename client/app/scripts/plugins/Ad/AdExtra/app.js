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
angular.module('olikerApp.Ad.AdExtra', [])
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
        $stateProvider.state('ad_payment', {
            url: '/ads/payment/:id?error_code',
            templateUrl: 'scripts/plugins/Ad/AdExtra/views/default/ad_extrday.html',
            resolve: getToken
        })
    });