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
angular.module('olikerApp.Ad', ['ui.router'])
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
        $stateProvider.state('ads', {
                url: '/ads?advertiser_type_id&category_id&min_price&max_price&sort&sortby&page&q&city_id&is_search_in_description&is_only_ads_with_images',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/ads.html',
                reloadOnSearch: false,
                resolve: getToken
            })
            .state('ad_edit', {
                url: '/ads/edit/:id',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/ad_edit.html',
                resolve: getToken
            })
            .state('ad_view', {
                url: '/ad/:id/:slug',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/ad_view.html',
                resolve: getToken
            })
            .state('ad', {
                url: '/ads/add?category_id&error_code',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/ad_add.html',
                resolve: getToken
            })
            .state('my_ads', {
                url: '/my_ads?page',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/my_ads.html',
                resolve: getToken
            })
            .state('category', {
                url: '/category/:id/:slug',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/category.html',
                resolve: getToken
            })
            .state('searches', {
                url: '/searches',
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/ad_searches.html',
                resolve: getToken
            });
    });