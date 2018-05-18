'use strict';
/**
 * @ngdoc service
 * @name olikerApp.countries
 * @description
 * # countries
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('CountriesFactory', function($resource) {
        return $resource('/api/v1/countries', {}, {
            get: {
                method: 'GET'
            }
        });
    });