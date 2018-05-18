'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:AdviewController
 * @description
 * # AdviewController
 * Controller of the olikerApp.Ads
 */
angular.module('olikerApp.Ad')
    .controller('AdviewController', function($rootScope, $stateParams, $location, $cookies, $window, $filter, $state, $timeout, md5, NgMap, AdFactory, AdsFactory, MessagesFactory, AdReportTypesFactory, AdReportFactory, $uibModal, AdFavoritesFactory, AdFavoriteFactory, flash) {
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Ad") + " " + $stateParams.slug;
        var vm = this;
        vm.myInterval = 5000;
        vm.noWrapSlides = false;
        vm.active = 0;
        vm.ad_id = $stateParams.id;
        vm.slideIndex2 = 1;
        vm.slides2 = [];
        vm.loader = false;
        vm.index = function() {
            vm.getAd();
            vm.relatedAds();
            vm.getAdReportType();
        };
        vm.getAd = function() {
            var params = {};
            params.adId = $stateParams.id;
            params.type = 'view';
            AdFactory.get(params, function(response) {
                vm.ad = response.data;
                if (angular.isDefined(vm.ad)) {
                    if ((angular.isDefined(vm.ad.attachment) && vm.ad.attachment.length > 0)) {
                        angular.forEach(vm.ad.attachment, function(attachment) {
                            attachment.big_thumb_url = '/images/big_thumb/Ad/' + attachment.id + '.' + md5.createHash('Ad' + attachment.id + 'png' + 'big_thumb') + '.png';
                        });
                    } else {
                        vm.ad.attachment = [];
                        vm.ad.attachment.push({
                            "big_thumb_url": "/images/ad_no_img.png",
                        });
                    }
                    if ((angular.isDefined(vm.ad.ad_owner.attachment) && vm.ad.ad_owner.attachment !== null)) {
                        var hash = md5.createHash('UserAvatar' + vm.ad.ad_owner.attachment.id + 'png' + 'normal_thumb');
                        vm.user_image = '/images/normal_thumb/UserAvatar/' + vm.ad.ad_owner.attachment.id + '.' + hash + '.png';
                    } else {
                        vm.user_image = "/images/user_no_img.png";
                    }
                }
                vm.getUserAds();
            });
        };
        vm.relatedAds = function() {
            var params = {};
            params.ad_id = $stateParams.id;
            params.filter = 'related';
            vm.relatedads = [];
            AdsFactory.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    var temp_ads = [];
                    var i = 0;
                    angular.forEach(response.data, function(ad) {
                        i++;
                        if (angular.isDefined(ad.attachment) && ad.attachment.length > 0) {
                            var hash = md5.createHash('Ad' + ad.attachment[0].id + 'png' + 'medium_thumb');
                            ad.image_name = '/images/medium_thumb/Ad/' + ad.attachment[0].id + '.' + hash + '.png';
                        } else {
                            ad.image_name = '/images/no_related_ad_image.png';
                        }
                        temp_ads.push(ad);
                        if (temp_ads.length === 4 || i === response.data.length) {
                            vm.relatedads.push(temp_ads);
                            temp_ads = [];
                        }
                    });
                }
            });
        };
        vm.showPhoneNumber = function() {
            var params = {};
            params.show_number = 1;
            params.adId = $stateParams.id;
            AdFactory.get(params, function(response) {
                vm.phone_number = response.data.phone_number;
            });
        };
        vm.getAdReportType = function() {
            var params = {};
            params.fields = "id,name";
            AdReportTypesFactory.get(params, function(response) {
                vm.ad_reports = response.data;
            });
        };
        vm.openMessageModal = function() {
            $uibModal.open({
                controller: "MessageModalInstanceController as vm",
                backdrop: 'true',
                templateUrl: 'scripts/plugins/Common/Message/views/default/message_modal.html',
                resolve: {
                    ad_id: function() {
                        return vm.ad_id;
                    },
                    other_user_id: function() {
                        return vm.ad.ad_owner.id;
                    }
                }
            });
        };
        vm.openReportModal = function() {
            $uibModal.open({
                controller: "ReportModalInstanceController as vm",
                backdrop: 'true',
                templateUrl: 'scripts/plugins/Ad/AdReport/views/default/ad_report_modal.html',
                resolve: {
                    ad: function() {
                        return vm.ad;
                    }
                }
            });
        };
        vm.getUserAds = function() {
            var params = {};
            params.user_id = vm.ad.ad_owner.id;
            params.ad_id = $stateParams.id;
            params.filter = "related";
            vm.userads = [];
            AdsFactory.get(params, function(response) {
                if (angular.isDefined(response.data)) {
                    var temp_ads = [];
                    var i = 0;
                    angular.forEach(response.data, function(user_ad) {
                        i++;
                        if (angular.isDefined(user_ad.attachment) && user_ad.attachment.length > 0) {
                            var hash = md5.createHash('Ad' + user_ad.attachment[0].id + 'png' + 'medium_thumb');
                            user_ad.image_name = '/images/medium_thumb/Ad/' + user_ad.attachment[0].id + '.' + hash + '.png';
                        } else {
                            user_ad.image_name = '/images/no_userad_image .png';
                        }
                        temp_ads.push(user_ad);
                        if (temp_ads.length === 4 || i === response.data.length) {
                            vm.userads.push(temp_ads);
                            temp_ads = [];
                        }
                    });
                }
            });
        };
        vm.index();
    });