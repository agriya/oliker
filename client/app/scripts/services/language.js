'use strict';
/**
 * @ngdoc service
 * @name olikerApp.languages
 * @description
 * # languages
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('LanguagesFactory', function($resource) {
        return $resource('/api/v1/languages', {}, {
            get: {
                method: 'GET'
            }
        });
    });