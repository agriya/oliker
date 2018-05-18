'use strict';
/**
 * @ngdoc service
 * @name olikerApp.ContactsFactory
 * @description
 * # ContactsFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('ContactsFactory', function($resource) {
        return $resource('/api/v1/contacts', {}, {
            create: {
                method: 'POST'
            }
        });
    });