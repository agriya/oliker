'use strict';
/**
 * @ngdoc service
 * @name oliker.refreshToken
 * @description
 * # refreshToken
 * Factory in the olikerApp.
 */
angular.module('base')
    .factory('refreshToken', ['$resource', function($resource) {
        return $resource('/api/v1/oauth/refresh_token', {}, {
            get: {
                method: 'GET'
            }
        });
  }]);