'use strict';
/**
 * @ngdoc service
 * @name olikerApp.MessagesFactory
 * @description
 * # MessagesFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Common.Message')
    .factory('MessagesFactory', function($resource) {
        return $resource('/api/v1/messages', {}, {
            create: {
                method: 'POST',
            }
        });
    })
    .factory('MessageViewFactory', function($resource) {
        return $resource('/api/v1/messages/:messageId', {}, {
            get: {
                method: 'GET',
                params: {
                    messageId: '@messageId'
                }
            }
        });
    });