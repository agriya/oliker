'use strict';
/**
 * @ngdoc service
 * @name olikerApp.postGateways
 * @description
 * # postGateways
 * Factory in the ofos.
 */
angular.module('base')
    .factory('postGateways', ['$resource', function($resource) {
        return $resource('/api/v1/post_gateways', {}, {
            save: {
                method: 'POST'
            }
        });
}]);