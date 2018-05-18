'use strict';
/**
 * @ngdoc service
 * @name olikerApp.WalletFactory
 * @description
 * # WalletFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('WalletFactory', function($resource) {
        return $resource('/api/v1/wallets', {}, {
            create: {
                method: 'POST'
            }
        });
    });