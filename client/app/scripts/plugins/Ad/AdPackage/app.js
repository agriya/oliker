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
angular.module('olikerApp.Ad.AdPackage', [])
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
        $stateProvider.state('ad_subscription_packages', {
                url: '/ad_subscription_packages',
                templateUrl: 'scripts/plugins/Ad/AdPackage/views/default/ad_subscription_packages.html',
                resolve: getToken
            })
            .state('user_ad_packages', {
                url: '/user_ad_packages?category_id&error_code',
                templateUrl: 'scripts/plugins/Ad/AdPackage/views/default/user_ad_packages.html',
                resolve: getToken
            });
    });