'use strict';
/**
 * @ngdoc service
 * @name olikerApp.CreditCardFactory
 * @description
 * # CreditCardFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Common.Paypal')
    .factory('CreditCardFactory', function($resource) {
        return $resource('/api/v1/paypal/vaults', {}, {
            create: {
                method: 'POST'
            }
        });
    })
    .factory('CreditCardListFactory', function($resource) {
        return $resource('/api/v1/paypal/vaults', {}, {
            get: {
                method: 'GET'
            }
        });
    })
    .factory('CreditCardRemoveFactory', function($resource) {
        return $resource('/api/v1/paypal/vaults/:vaultId', {}, {
            remove: {
                method: 'DELETE',
                params: {
                    vaultId: '@vaultId',
                }
            }
        });
    });