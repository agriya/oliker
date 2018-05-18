'use strict';
/**
 * @ngdoc service
 * @name olikerApp.PaymentGatewayFactory
 * @description
 * # PaymentGatewayFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('PaymentGatewayFactory', function($resource) {
        return $resource('/api/v1/payment_gateways/list', {}, {
            get: {
                method: 'GET'
            }
        });
    });