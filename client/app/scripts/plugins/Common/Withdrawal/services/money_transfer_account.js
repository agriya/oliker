'use strict';
/**
 * @ngdoc service
 * @name olikerApp.MoneyTransferAccountFactory
 * @description
 * # MoneyTransferAccountFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('MoneyTransferAccountFactory', function($resource) {
        return $resource('/api/v1/users/:user_id/money_transfer_accounts/:account_id', {}, {
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
            delete: {
                method: 'DELETE',
                params: {
                    user_id: '@user_id',
                    account_id: '@account_id'
                }
            }
        });
    });