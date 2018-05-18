'use strict';
/**
 * @ngdoc service
 * @name olikerApp.ads
 * @description
 * # ads
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad')
    .factory('AdsFactory', function($resource) {
        return $resource('/api/v1/ads', {}, {
            get: {
                method: 'GET'
            },
            create: {
                method: 'POST'
            }
        });
    })
    .factory('MyAdsFactory', function($resource) {
        return $resource('/api/v1/me/ads', {}, {
            get: {
                method: 'GET'
            }
        });
    })
    .factory('AdFactory', function($resource) {
        return $resource('/api/v1/ads/:adId', {}, {
            remove: {
                method: 'DELETE',
                params: {
                    adId: '@adId'
                }
            },
            get: {
                method: 'GET',
                params: {
                    adId: '@adId'
                },
            },
            update: {
                method: 'PUT',
                params: {
                    adId: '@adId'
                }
            }
        });
    });