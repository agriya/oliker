'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:AdFavoritesController
 * @description
 * # AdFavoritesController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad.AdFavorite')
    .controller('AdFavoritesController', function($rootScope, AdFavoritesFactory,SweetAlert, ScrollPageFactory, AdFavoriteFactory, flash, $filter) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Ad Favorites");
        vm.head_title = $filter("translate")("Ad Favorites");
        vm.head_description = $filter("translate")("Manage All Your Ad Favorites");
        vm.maxSize = 5;
        vm.index = function() {
            vm.active_tab1 = "active";
            vm.favorite_tab = "active";
            vm.loader = true;
            vm.current_page = 1;
            vm.ad_favorites = [];
            vm.getAdFavorites();
        };
        vm.getAdFavorites = function() {
            var params = {};
            params.page = vm.current_page;
            params.user_id = $rootScope.user.id;
            AdFavoritesFactory.get(params, function(response) {
                if (angular.isDefined(response._metadata)) {
                    vm.total_items = response._metadata.total;
                    vm.current_page = response._metadata.current_page;
                    vm.items_per_page = response._metadata.per_page;
                    vm.no_of_pages = response._metadata.last_page;
                }
                if (angular.isDefined(response.data)) {
                    angular.forEach(response.data, function(ad_favorite) {
                        vm.ad_favorites.push(ad_favorite);
                    });
                }
                vm.loader = false;
            });
        };
        vm.AdFavoritesRemove = function(ad_favorite_id, index) {
              SweetAlert.swal({
                title: $filter("translate")("Are you sure you want to delete this ad favorite?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0e6cfb",
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                animation:false,
            }, function(isConfirm) {
                if (isConfirm) {
            AdFavoriteFactory.remove({
                adFavoriteId: ad_favorite_id
            }, function(response) {
                if (response.error.code === 0) {
                    vm.ad_favorites.splice(index, 1);
                }
            });
                }
                });
        };
        vm.paginate = function() {
            vm.current_page = parseInt(vm.current_page);
            vm.getAdFavorites();
            ScrollPageFactory.scrollPageTop();
        };
        vm.index();
    });