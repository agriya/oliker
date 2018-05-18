'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:MyAdsListController
 * @description
 * # MyAdsListController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('MyAdsListController', function($rootScope, MyAdsFactory, AdFactory,SweetAlert, ScrollPageFactory, flash, $filter, $state, md5) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("My Ads");
        vm.head_title = $filter("translate")("My Ads");
        vm.head_description = $filter("translate")("Manage All Your Ads");
        vm.current_page = 1;
        vm.index = function() {
            vm.active_tab1 = "active";
            vm.ad_tab = "active";
            vm.loader = true;
            vm.getMyAds();
        };
        vm.getMyAds = function() {
            var params = {};
            params.sort = 'id';
            params.page = vm.current_page;
            MyAdsFactory.get(params, function(response) {
                vm.my_ads = response.data;
                if (angular.isDefined(response._metadata)) {
                    vm.total_items = response._metadata.total;
                    vm.current_page = response._metadata.current_page;
                    vm.items_per_page = response._metadata.per_page;
                    vm.no_of_pages = response._metadata.last_page;
                    vm.ads_count = response._metadata.total;
                }
                angular.forEach(vm.my_ads, function(ad, key) {
                    if (angular.isDefined(ad.attachment) && ad.attachment.length > 0) {
                        var hash = md5.createHash('Ad' + ad.attachment[0].id + 'png' + 'micro_thumb');
                        vm.my_ads[key].image_name = '/images/micro_thumb/Ad/' + ad.attachment[0].id + '.' + hash + '.png';
                    } else {
                        vm.my_ads[key].image_name = '/images/my_ad_no_image.png';
                    }
                });
                vm.loader = false;
            });
        };
        vm.removeAd = function(adId, index) {
              SweetAlert.swal({
                title: $filter("translate")("Are you sure you want to delete this ad?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0e6cfb",
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                animation:false,
            }, function(isConfirm) {
                if (isConfirm) {
                      var params = {};
            params.adId = adId;
            var flashMessage;
            AdFactory.remove(params, function(response) {
                vm.response = response;
                if (response.error.code === 0) {
                    flashMessage = $filter("translate")("Ad Deleted Suceessfully");
                    flash.set(flashMessage, 'success', false);
                }
            });
            vm.my_ads.splice(index, 1);
                }
            });

          
        };
        vm.paginate = function() {
            vm.current_page = parseInt(vm.current_page);
            vm.getMyAds();
            ScrollPageFactory.scrollPageTop();
            $state.go('my_ads', {
                page: vm.current_page
            }, {
                'notify': false
            });
        };
        vm.index();
    });