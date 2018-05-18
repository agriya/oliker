'use strict';
/**
 * @ngdoc service
 * @name olikerApp.adfavorites & remove favorites
 * @description
 * # adfavorites & remove favorites
 * Factory in the olikerApp.
 */
angular.module('olikerApp.Ad.AdFavorite')
    .factory('AdFavoritesFactory', function($resource) {
        return $resource('/api/v1/ad_favorites', {}, {
            create: {
                method: 'POST'
            },
            get: {
                method: 'GET'
            }
        });
    })
    .factory('AdFavoriteFactory', function($resource) {
        return $resource('/api/v1/ad_favorites/:adFavoriteId', {}, {
            remove: {
                method: 'DELETE',
                params: {
                    adFavoriteId: '@adFavoriteId'
                }
            }
        });
    });