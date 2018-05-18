'use strict';
/**
 * @ngdoc service
 * @name olikerApp.categories
 * @description
 * # categories
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad')
    .factory('CategoriesFactory', function($resource) {
        return $resource('/api/v1/categories', {}, {
            get: {
                method: 'GET'
            }
        });
    })
    .factory('CategoryAdCountCheckFactory', function($resource) {
        return $resource('/api/v1/categories/:categoryId/check_payment', {}, {
            get: {
                method: 'GET',
                params: {
                    categoryId: '@categoryId'
                }
            }
        });
    })
    .factory('CategoryFactory', function($resource) {
        return $resource('/api/v1/categories/:categoryId', {}, {
            get: {
                method: 'GET',
                params: {
                    categoryId: '@categoryId'
                }
            }
        });
    });