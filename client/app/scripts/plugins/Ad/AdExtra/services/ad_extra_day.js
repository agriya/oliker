'use strict';
/**
 * @ngdoc service
 * @name olikerApp.Ads.AdExtraDaysFactory
 * @description
 * # AdExtraDaysFactory
 * Factory in the olikerApp.Ads.
 */
angular.module('olikerApp.Ad.AdExtra')
    .factory('AdExtraDaysFactory', function($resource) {
        return $resource('/api/v1/ad_extra_days', {}, {
            get: {
                method: 'GET'
            }
        });
    });