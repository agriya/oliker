'use strict';
/**
 * @ngdoc service
 * @name olikerApp.refreshToken
 * @description
 * # refreshToken
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('refreshToken', function($resource) {
        return $resource('/api/v1/oauth/refresh_token', {}, {
            get: {
                method: 'GET'
            }
        });
    });