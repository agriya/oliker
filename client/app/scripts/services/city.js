'use strict';
/**
 * @ngdoc service
 * @name olikerApp.cities
 * @description
 * # cities
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('CitiesFactory', function($resource) {
        return $resource('/api/v1/cities', {}, {
            get: {
                method: 'GET'
            }
        });
    });