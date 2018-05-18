'use strict';
/**
 * @ngdoc service
 * @name olikerApp.Ads.AdFormFieldFactory
 * @description
 * # AdFormFieldFactory
 * Factory in the olikerApp.Ads.
 */
angular.module('olikerApp.Ad')
    .factory('AdFormFieldFactory', function($resource) {
        return $resource('/api/v1/ad_form_fields', {}, {
            get: {
                method: 'GET'
            }
        });
    });