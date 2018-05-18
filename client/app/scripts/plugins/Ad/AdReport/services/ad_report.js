'use strict';
/**
 * @ngdoc service
 * @name olikerApp.adsreports
 * @description
 * # adreport
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad.AdReport')
    .factory('AdReportFactory', function($resource) {
        return $resource('/api/v1/ad_reports', {}, {
            create: {
                method: 'POST'
            }
        });
    });