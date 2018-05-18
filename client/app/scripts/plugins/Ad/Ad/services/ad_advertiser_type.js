'use strict';
/**
 * @ngdoc service
 * @name olikerApp.Ads.AdsAdvertiserTypeFactory
 * @description
 * # AdsAdvertiserTypeFactory
 * Factory in the olikerApp.Ads.
 */
angular.module('olikerApp.Ad')
    .factory('AdsAdvertiserTypeFactory', function($resource) {
        return $resource('/api/v1/advertiser_types', {}, {
            get: {
                method: 'GET'
            }
        });
    });