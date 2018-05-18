'use strict';
/**
 * @ngdoc service
 * @name olikerApp.SettingsFactory
 * @description
 * # SettingsFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('SettingsFactory', function($resource) {
        return $resource('/api/v1/settings', {}, {
            get: {
                method: 'GET'
            }
        });
    });