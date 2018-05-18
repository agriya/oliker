'use strict';
/**
 * @ngdoc service
 * @name olikerApp.ProviderFactory
 * @description
 * # ProviderFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('ProviderFactory', function($resource) {
        return $resource('/api/v1/providers', {}, {
            get: {
                method: 'GET'
            }
        });
    });