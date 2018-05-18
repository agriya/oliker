'use strict';
/**
 * @ngdoc service
 * @name instagramApp.userNotificationFactory
 * @description
 * # userNotificationFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('userNotificationFactory', function($resource) {
        return $resource('/api/v1/user_notifications/:userNotificationId', {}, {
            get: {
                method: 'GET',
                params: {
                    userNotificationId: '@userNotificationId'
                }
            },
            update: {
                method: 'PUT',
                params: {
                    userNotificationId: '@userNotificationId'
                }
            },
        });
    });