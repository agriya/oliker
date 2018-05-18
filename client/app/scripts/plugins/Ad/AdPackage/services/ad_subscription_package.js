'use strict';
/**
 * @ngdoc service
 * @name olikerApp.Ads.AdSubscriptionPackagesFactory
 * @description
 * # AdSubscriptionPackagesFactory
 * Factory in the olikerApp.Ads.
 */
angular.module('olikerApp.Ad.AdPackage')
    .factory('AdSubscriptionPackagesFactory', function($resource) {
        return $resource('/api/v1/user_ad_packages', {}, {
            get: {
                method: 'GET'
            },
            create: {
                method: 'POST'
            }
        });
    });