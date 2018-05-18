'use strict';
/**
 * @ngdoc service
 * @name olikerApp.page
 * @description
 * # page
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('PageFactory', function($resource) {
        return $resource('/api/v1/pages/:id', {}, {
            get: {
                method: 'GET',
                params: {
                    id: '@id'
                }
            }
        });
    })
    .factory('PagesFactory', function($resource) {
        return $resource('/api/v1/pages', {}, {
            get: {
                method: 'GET'
            }
        });
    });