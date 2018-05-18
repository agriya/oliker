'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:TrendingAdsListController
 * @description
 * # AdsListController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('TrendingAdsListController', function(AdsFactory, md5) {
        var vm = this;
        vm.myInterval = 5000;
        vm.noWrapSlides = false;
        vm.active = 0;
        vm.index = function() {
            vm.getAds();
        };
        vm.getAds = function() {
            vm.ads = [];
            AdsFactory.get({
                limit: 20
            }, function(response) {
                if (angular.isDefined(response.data)) {
                    var temp_ads = [];
                    var i = 0;
                    angular.forEach(response.data, function(ad) {
                        i++;
                        if (angular.isDefined(ad.attachment) && ad.attachment.length > 0) {
                            var hash = md5.createHash('Ad' + ad.attachment[0].id + 'png' + 'medium_thumb');
                            ad.image_name = '/images/medium_thumb/Ad/' + ad.attachment[0].id + '.' + hash + '.png';
                        } else {
                            ad.image_name = '/images/no_adimage-230x219.png';
                        }
                        temp_ads.push(ad);
                        if (temp_ads.length === 4 || i === response.data.length) {
                            vm.ads.push(temp_ads);
                            temp_ads = [];
                        }
                    });
                }
            });
        };
        vm.index();
    });