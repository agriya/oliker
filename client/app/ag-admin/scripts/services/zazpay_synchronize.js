'use strict';
/**
 * @ngdoc service
 * @name olikerApp.zazpaySynchronize
 * @description
 * # zazpaySynchronize
 * Factory in the olikerApp.
 */
angular.module('base')
    .factory('zazpaySynchronize', ['$resource', function($resource) {
        return $resource('/api/v1/payment_gateways/zazpay_synchronize', {}, {
            get: {
                method: 'GET'
            }
        });
}]);