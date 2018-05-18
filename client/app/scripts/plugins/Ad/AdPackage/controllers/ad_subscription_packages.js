'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:AdSubscriptionPackagesController
 * @description
 * # AdSubscriptionPackagesController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad.AdPackage')
    .controller('AdSubscriptionPackagesController', function($rootScope, $stateParams, $location, $filter, $state, $timeout, md5, AdSubscriptionPackagesFactory) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Adsubscriptionpackages");
        vm.current_page = 1;
        vm.head_title = $filter("translate")("Subscribed Packages");
        vm.head_description = $filter("translate")("Manage All Your Subscribed Packages");
        vm.index = function() {
            vm.active_tab6 = "active";
            vm.subscription_package_tab = "active";
            vm.loader = true;
            vm.getSubscriptionPackages();
        };
        vm.getSubscriptionPackages = function() {
            var params = {};
            params.page = vm.current_page;
            params.user_id = $rootScope.user.id;
            AdSubscriptionPackagesFactory.get(params, function(response) {
                vm.subscription_packages = response.data;
                if (angular.isDefined(response._metadata)) {
                    vm.total_items = response._metadata.total;
                    vm.current_page = response._metadata.current_page;
                    vm.items_per_page = response._metadata.per_page;
                    vm.no_of_pages = response._metadata.last_page;
                    vm.ads_count = response._metadata.total;
                }
                vm.loader = false;
            });
        };
        vm.paginate = function() {
            vm.current_page = parseInt(vm.current_page);
            vm.getSubscriptionPackages();
            ScrollPageFactory.scrollPageTop();
        };
        vm.index();
    });