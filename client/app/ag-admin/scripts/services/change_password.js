'use strict';
/**
 * @ngdoc service
 * @name olikerApp.ChangePasswordFactory
 * @description
 * # ChangePasswordFactory
 * Factory in the olikerApp.
 */
angular.module('base')
    .factory('ChangePasswordFactory', function($resource) {
        return $resource('/api/v1/users/:id/change_password', {}, {
            update: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
    })