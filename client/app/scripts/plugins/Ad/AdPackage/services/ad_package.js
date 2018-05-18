'use strict';
/**
 * @ngdoc service
 * @name olikerApp.Ads.AdPackagesFactory
 * @description
 * # AdPackagesFactory
 * Factory in the olikerApp.Ads.
 */
angular.module('olikerApp.Ad.AdPackage')
    .factory('AdPackagesFactory', function($resource) {
        return $resource('/api/v1/ad_packages', {}, {
            get: {
                method: 'GET'
            }
        });
    });