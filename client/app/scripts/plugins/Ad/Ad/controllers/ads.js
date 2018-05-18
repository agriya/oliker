'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:AdsListController
 * @description
 * # AdsListController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('AdsListController', function($rootScope, $location, $uibModal, $cookies, AdsFactory, $filter, $state, AdsAdvertiserTypeFactory, AdFavoritesFactory, ScrollPageFactory, AdFavoriteFactory, CategoriesFactory, md5, $stateParams, AdSearchFactory, flash, $scope) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Ads");
        vm.is_category_collapsed = false;
        vm.is_price_collapsed = false;
        vm.is_advertiser_type_collapsed = false;
        vm.is_search_collapsed = false;
        if (angular.isDefined($stateParams.q) && $stateParams.q !== "") {
            vm.q = $stateParams.q;
        }
        if (angular.isDefined($stateParams.category_id) && $stateParams.category_id !== "") {
            vm.category_id = $stateParams.category_id;
        }
        if (angular.isDefined($stateParams.city_id) && $stateParams.city_id !== "") {
            vm.city_id = $stateParams.city_id;
        }
        vm.current_page = 1;
        vm.category_sort = {};
        vm.advertiser_type_id = '';
        vm.min_price = '';
        vm.max_price = '';
        vm.advertiser_typesort = {};
        vm.filter = 'recent';
        vm.ad_sort = "recent";
        vm.sortby = 'DESC';
        vm.is_search_in_description = '';
        vm.is_only_ads_with_images = '';
        vm.min = '';
        vm.max = '';
        vm.index = function() {
            vm.getAds();
            vm.getCategories();
            vm.getTopAds();
            vm.getAdvertiserType();
            vm.max_size = 5;
            vm.loader = true;
        };
        vm.getAds = function() {
            var params = {};
            if (angular.isDefined(vm.filter) && vm.filter !== null) {
                params.filter = vm.filter;
            }
            if (angular.isDefined(vm.advertiser_type_id) && vm.advertiser_type_id !== "") {
                params.advertiser_type_id = vm.advertiser_type_id;
            }
            if (angular.isDefined(vm.category_id) && vm.category_id !== null) {
                params.category_id = vm.category_id;
                var v = vm.category_id;
                v.split(',')
                    .forEach(function(e) {
                        vm.category_sort[e] = true;
                    });
            }
            if (angular.isDefined(vm.is_search_in_description) && vm.is_search_in_description === true) {
                params.is_search_in_description = vm.is_search_in_description;
            }
            if (angular.isDefined(vm.is_only_ads_with_images) && vm.is_only_ads_with_images === true) {
                params.is_only_ads_with_images = vm.is_only_ads_with_images;
            }
            if (angular.isDefined(vm.sort) && vm.sort !== "") {
                params.sort = vm.sort;
            }
            if (angular.isDefined(vm.sortby) && vm.sortby !== "") {
                params.sortby = vm.sortby;
            }
            if (angular.isDefined(vm.min_price) && vm.min_price !== "") {
                params.min_price = vm.min_price;
            }
            if (angular.isDefined(vm.max_price) && vm.max_price !== "") {
                params.max_price = vm.max_price;
            }
            if (angular.isDefined(vm.city_id) && vm.city_id !== null) {
                params.city_id = vm.city_id;
            }
            if (angular.isDefined(vm.q) && vm.q !== null) {
                params.q = vm.q;
            }
            params.page = vm.current_page;
            AdsFactory.get(params, function(response) {
                vm.ads = response.data;
                if (angular.isDefined(response._metadata)) {
                    vm.total_items = response._metadata.total;
                    vm.current_page = response._metadata.current_page;
                    vm.items_per_page = response._metadata.per_page;
                    vm.no_of_pages = response._metadata.last_page;
                    vm.ads_count = response._metadata.total;
                    if (vm.min === '' && vm.max === '') {
                        vm.min = Number(response._metadata.min_price);
                        vm.max = Number(response._metadata.max_price);
                        vm.slider = {
                            minValue: vm.min,
                            maxValue: vm.max,
                            options: {
                                floor: vm.min,
                                ceil: vm.max,
                                step: 100
                            }
                        };
                    }
                }
                angular.forEach(vm.ads, function(ad, key) {
                    if (angular.isDefined(ad.attachment) && ad.attachment.length > 0) {
                        var hash = md5.createHash('Ad' + ad.attachment[0].id + 'png' + 'normal_thumb');
                        vm.ads[key].image_name = '/images/normal_thumb/Ad/' + ad.attachment[0].id + '.' + hash + '.png';
                    } else {
                        vm.ads[key].image_name = '/images/No_ads_img.png';
                    }
                });
                vm.loader = false;
            });
        };
        var unregisterSlideEnded = $scope.$on("slideEnded", function() {
            vm.min_price = vm.slider.minValue;
            vm.max_price = vm.slider.maxValue;
            vm.getAds();
            vm.getTopAds();
            vm.updateURL();
            ScrollPageFactory.scrollPageTop();
        });
        $scope.$on("$destroy", function() {
            unregisterSlideEnded();
        });
        vm.getTopAds = function() {
            var params = {};
            if (angular.isDefined(vm.filter) && vm.filter !== null) {
                params.filter = vm.filter;
            }
            if (angular.isDefined(vm.advertiser_type_id) && vm.advertiser_type_id !== "") {
                params.advertiser_type_id = vm.advertiser_type_id;
            }
            if (angular.isDefined(vm.min_price) && vm.min_price !== "") {
                params.min_price = vm.min_price;
            }
            if (angular.isDefined(vm.max_price) && vm.max_price !== "") {
                params.max_price = vm.max_price;
            }
            if (angular.isDefined(vm.sort) && vm.sort !== "") {
                params.sort = vm.sort;
            }
            if (angular.isDefined(vm.sortby) && vm.sortby !== "") {
                params.sortby = vm.sortby;
            }
            if (angular.isDefined(vm.is_search_in_description) && vm.is_search_in_description === true) {
                params.is_search_in_description = vm.is_search_in_description;
            }
            if (angular.isDefined(vm.is_only_ads_with_images) && vm.is_only_ads_with_images === true) {
                params.is_only_ads_with_images = vm.is_only_ads_with_images;
            }
            params.limit = 5;
            params.city_id = vm.city_id;
            params.is_show_as_top_ads = true;
            params.q = vm.q;
            params.category_id = vm.category_id;
            AdsFactory.get(params, function(response) {
                vm.top_ads = response.data;
                angular.forEach(vm.top_ads, function(top_ad, key) {
                    if (angular.isDefined(top_ad.attachment) && top_ad.attachment.length > 0) {
                        var hash = md5.createHash('Ad' + top_ad.attachment[0].id + 'png' + 'normal_thumb');
                        vm.top_ads[key].image_name = '/images/normal_thumb/Ad/' + top_ad.attachment[0].id + '.' + hash + '.png';
                    } else {
                        vm.top_ads[key].image_name = '/images/No_ads_img.png';
                    }
                });
            });
        };
        vm.getAdvertiserType = function() {
            AdsAdvertiserTypeFactory.get(function(response) {
                vm.advertiser_types = response.data;
            });
        };
        vm.paginate = function() {
            vm.current_page = parseInt(vm.current_page);
            vm.getAds();
            vm.updateURL();
            ScrollPageFactory.scrollPageTop();
        };
        vm.getCategories = function() {
            var params = {};
            params.parent_id = 0;
            params.sort = 'name';
            params.sortby = "ASC";
            params.limit = "all";
            CategoriesFactory.get(params, function(response) {
                vm.categories = response.data;
            });
        };
        vm.categoryFilter = function() {
            vm.currentPage = 1;
            vm.items = vm.getcategoryItems(vm.category_sort);
            vm.category_id = (vm.items.length !== 0) ? vm.items.join() : null;
            vm.getAds();
            vm.getTopAds();
            vm.updateURL();
            ScrollPageFactory.scrollPageTop();
        };
        vm.getcategoryItems = function(obj) {
            var checked = [];
            for (var key in obj) {
                if (obj[key]) {
                    checked.push(key);
                }
            }
            return checked;
        };
        vm.setAdvertiserType = function() {
            vm.currentPage = 1;
            vm.advertisertypes = vm.getAdvertisertypeAds(vm.advertiser_typesort);
            vm.advertiser_type_id = (vm.advertisertypes.length !== 0) ? vm.advertisertypes.join() : null;
            vm.getAds();
            vm.getTopAds();
            vm.updateURL();
            ScrollPageFactory.scrollPageTop();
        };
        vm.getAdvertisertypeAds = function(obj) {
            var selectadvertisertype = [];
            for (var key in obj) {
                if (obj[key]) {
                    selectadvertisertype.push(key);
                }
            }
            return selectadvertisertype;
        };
        vm.sortAd = function(type) {
            vm.ad_sort = type;
            if (vm.ad_sort === 'low_price') {
                vm.sort = 'price';
                vm.filter = '';
                vm.sortby = 'ASC';
            } else if (vm.ad_sort === 'high_price') {
                vm.sort = 'price';
                vm.sortby = 'DESC';
                vm.filter = '';
            } else {
                vm.filter = type;
                vm.sort = '';
                vm.sortby = '';
            }
            vm.getAds();
            vm.getTopAds();
            vm.updateURL();
        };
        vm.addFavorite = function(ad_id, index, type) {
            var params = {};
            vm.ad_type = type;
            params.ad_id = ad_id;
            AdFavoritesFactory.create(params, function(response) {
                if (vm.ad_type === 'top_ad') {
                    vm.top_ads[index].ad_favorite.push(response);
                } else {
                    vm.ads[index].ad_favorite.push(response);
                }
                if (response.error.code === 0) {
                    var flashMessage;
                    flashMessage = $filter("translate")("Added to Favorites");
                    flash.set(flashMessage, 'success', false);
                }
            });
            return false;
        };
        vm.removeFavorite = function(ad_Favorite_id, $index) {
            var params = {};
            params.adFavoriteId = ad_Favorite_id;
            AdFavoriteFactory.remove(params, function(response) {
                if (vm.ad_type === 'top_ad') {
                    vm.top_ads[$index].ad_favorite = [];
                } else {
                    vm.ads[$index].ad_favorite = [];
                }
                if (response.error.code === 0) {
                    var flashMessage;
                    flashMessage = $filter("translate")("Removed from Favorites");
                    flash.set(flashMessage, 'success', false);
                }
            });
            return false;
        };
        vm.updateURL = function() {
            var params = {};
            if (angular.isDefined(vm.advertiser_type_id) && vm.advertiser_type_id !== "") {
                params.advertiser_type_id = vm.advertiser_type_id;
            }
            if (angular.isDefined(vm.category_id) && vm.category_id !== "") {
                params.category_id = vm.category_id;
            }
            if (angular.isDefined(vm.sort) && vm.sort !== "") {
                params.sort = vm.sort;
            }
            if (angular.isDefined(vm.min_price) && vm.min_price !== "") {
                params.min_price = vm.min_price;
            }
            if (angular.isDefined(vm.max_price) && vm.max_price !== "") {
                params.max_price = vm.max_price;
            }
            if (angular.isDefined(vm.sortby) && vm.sortby !== "") {
                params.sortby = vm.sortby;
            }
            if (angular.isDefined(vm.current_page) && vm.current_page !== "") {
                params.page = vm.current_page;
            }
            if (angular.isDefined(vm.is_search_in_description) && vm.is_search_in_description === true) {
                params.is_search_in_description = vm.is_search_in_description;
            }
            if (angular.isDefined(vm.is_search_in_description) && vm.is_search_in_description === false) {
                params.is_search_in_description = "";
            }
            if (angular.isDefined(vm.is_only_ads_with_images) && vm.is_only_ads_with_images === true) {
                params.is_only_ads_with_images = vm.is_only_ads_with_images;
            }
            if (angular.isDefined(vm.is_only_ads_with_images) && vm.is_only_ads_with_images === false) {
                params.is_only_ads_with_images = "";
            }
            $state.go('ads', params, {
                'notify': false
            });
        };
        vm.saveSearch = function() {
            var params = {};
            params.keyword = $stateParams.q;
            params.category_id = vm.category_id;
            AdSearchFactory.create(params, function(response) {
                if (response.error.code === 0) {
                    var flashMessage;
                    flashMessage = $filter("translate")("Ad Search saved");
                    flash.set(flashMessage, 'success', false);
                }
            });
        };
        vm.getAdSearchInDesciption = function(is_search_in_description) {
            vm.is_search_in_description = is_search_in_description;
            vm.getTopAds();
            vm.getAds();
            vm.updateURL();
        };
        vm.getAdsOnlyWithImages = function(is_only_ads_with_images) {
            vm.is_only_ads_with_images = is_only_ads_with_images;
            vm.getTopAds();
            vm.getAds();
            vm.updateURL();
        };
        vm.saveSerach = function() {
            var params = {};
            params.keyword = $stateParams.q;
            params.category_id = vm.category_id;
            AdSearchFactory.create(params, function(response) {
                if (response.error.code === 0) {
                    var flashMessage;
                    flashMessage = $filter("translate")("Ad Search saved");
                    flash.set(flashMessage, 'success', false);
                }
            });
        };
        vm.index();
    });