'use strict';
/**
 * @ngdoc service
 * @name olikerApp.stats
 * @description
 * # statsticscount
 * Factory in the olikersApp.
 */
angular.module('olikerApp')
    .factory('StatsFactory', function($resource) {
        return $resource('/api/v1/stats', {}, {
            get: {
                method: 'GET'
            }
        });
    });