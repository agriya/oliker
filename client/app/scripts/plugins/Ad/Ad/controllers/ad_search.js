'use strict';
/**
 * @ngdoc function
 * @name sheermeApp.controller:AdSearchController
 * @description
 * # AdSearchController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('AdSearchController', function($rootScope, AdSearchFactory,SweetAlert, ScrollPageFactory, AdSearchDeleteFactory, flash, $filter) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Ad Searches");
        vm.head_title = $filter("translate")("Ad Search");
        vm.head_description = $filter("translate")("Manage All Your Ad Searches");
        vm.maxSize = 5;
        vm.index = function() {
            vm.active_tab1 = "active";
            vm.search_tab = "active";
            vm.loader = true;
            vm.current_page = 1;
            vm.ad_searches = [];
            vm.getAdSearch();
        };
        vm.getAdSearch = function() {
            var params = {};
            params.page = vm.current_page;
            params.user_id = $rootScope.user.id;
            AdSearchFactory.get(params, function(response) {
                if (angular.isDefined(response._metadata)) {
                    vm.total_items = response._metadata.total;
                    vm.current_page = response._metadata.current_page;
                    vm.items_per_page = response._metadata.per_page;
                    vm.no_of_pages = response._metadata.last_page;
                    vm.ads_count = response._metadata.total;
                }
                if (angular.isDefined(response.data)) {
                    angular.forEach(response.data, function(ad_Search) {
                        vm.ad_searches.push(ad_Search);
                    });
                }
                vm.loader = false;
            });
        };
        vm.AdSearchesRemove = function(ad_Search_id, index) {
             SweetAlert.swal({
                title: $filter("translate")("Are you sure you want to delete this ad search?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0e6cfb",
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                animation:false,
            }, function(isConfirm) {
                if (isConfirm) {
            AdSearchDeleteFactory.remove({
                id: ad_Search_id
            }, function(response) {
                if (response.error.code === 0) {
                    vm.ad_searches.splice(index, 1);
                }
            });
            }
        });
        };
        vm.paginate = function() {
            vm.current_page = parseInt(vm.current_page);
            vm.getAdSearch();
            ScrollPageFactory.scrollPageTop();
        };
        vm.index();
    });