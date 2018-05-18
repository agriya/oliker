'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:FavoriteAdControlller
 * @description
 * # FavoriteAdControlller
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad.AdFavorite')
    .controller('FavoriteAdController', function($rootScope, $scope, AdFavoritesFactory, AdFavoriteFactory, flash, $filter) {
        var vm = this;
        vm.addFavorite = function(ad_id, index) {
            var params = {};
            params.ad_id = ad_id;
            AdFavoritesFactory.create(params, function(response) {
                vm.item.ad_favorite.push(response);
                if (response.error.code === 0) {
                    var flashMessage;
                    flashMessage = $filter("translate")("Added to Favorites");
                    flash.set(flashMessage, 'success', false);
                }
            });
            return false;
        };
        vm.removeFavorite = function(ad_Favorite_id, index) {
            var params = {};
            params.adFavoriteId = ad_Favorite_id;
            AdFavoriteFactory.remove(params, function(response) {
                vm.item.ad_favorite = [];
                if (response.error.code === 0) {
                    var flashMessage;
                    flashMessage = $filter("translate")("Removed from Favorites");
                    flash.set(flashMessage, 'success', false);
                }
            });
            return false;
        };
    });