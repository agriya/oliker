'use strict';
/**
 * @ngdoc service
 * @name olikerApp.AttachmentDeleteFactory
 * @description
 * # AttachmentDeleteFactory
 * Factory in the olikerApp
 */
angular.module('olikerApp')
    .factory('AttachmentDeleteFactory', function($resource) {
        return $resource('/api/v1/attachments/:attachmentId', {}, {
            remove: {
                method: 'DELETE',
                params: {
                    attachmentId: '@attachmentId'
                }
            }
        });
    });