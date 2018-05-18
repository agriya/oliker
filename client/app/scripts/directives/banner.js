'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:bannerDirective
 * @description
 * # bannerDirective
 * Directive of the olikerApp
 */
angular.module('olikerApp')
    .directive('banner', function() {
        return {
            templateUrl: 'views/banner.html',
            restrict: 'EA',
            scope: {
                position: '@',
            },
            controller: 'BannerController',
            controllerAs: 'vm',
            link: function postLink() {}
        };
    });