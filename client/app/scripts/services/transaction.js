'use strict';
/**
 * @ngdoc service
 * @name olikerApp.TransactionFactory
 * @description
 * # TransactionFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('TransactionFactory', function($resource) {
        return $resource('/api/v1/users/:user_id/transactions', {}, {
            get: {
                method: 'GET',
                params: {
                    user_id: '@user_id'
                }
            }
        });
    });