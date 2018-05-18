'use strict';
/**
 * @ngdoc service
 * @name olikerApp.cashWithdrawalsFactory
 * @description
 * # cashWithdrawalsFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('cashWithdrawalsFactory', function($resource) {
        return $resource('/api/v1/users/:user_id/user_cash_withdrawals', {}, {
            get: {
                method: 'GET',
                params: {
                    user_id: '@user_id'
                }
            },
            save: {
                method: 'POST',
                params: {
                    user_id: '@user_id'
                }
            },
        });
    });