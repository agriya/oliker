'use strict';
/**
 * @ngdoc service
 * @name olikerApp.AdSearchFactory
 * @description
 * # AdSearchFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad')
    .factory('AdSearchFactory', function($resource) {
        return $resource('/api/v1/ad_searches', {}, {
            get: {
                method: 'GET'
            },
            create: {
                method: 'POST'
            }
        });
    })
    .factory('AdSearchDeleteFactory', function($resource) {
        return $resource('/api/v1/ad_searches/:id', {}, {
            remove: {
                method: 'DELETE'
            }
        });
    });