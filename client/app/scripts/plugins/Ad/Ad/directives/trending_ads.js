'use strict';
/**
 * @ngdoc service
 * @name olikerApp.categories
 * @description
 * # categories
 * Directive in the olikerApp.
 */
angular.module('olikerApp.Ad')
    .directive('trendingAds', function() {
        return {
            templateUrl: 'scripts/plugins/Ad/Ad/views/default/trending_ads.html',
            restrict: 'EA',
            replace: true,
            scope: true,
            controller: 'TrendingAdsListController',
            controllerAs: 'vm',
            link: function postLink(scope, element, attr) {}
        };
    });