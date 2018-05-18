'use strict';
/**
 * @ngdoc service
 * @name olikerApp.adsreportTypes
 * @description
 * # adreportType
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad.AdReport')
    .factory('AdReportTypesFactory', function($resource) {
        return $resource('/api/v1/ad_report_types', {}, {
            get: {
                method: 'GET'
            }
        });
    });