'use strict';
/**
 * @ngdoc function
 * @name sheermeApp.controller:CategoryController
 * @description
 * # CategoryController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('CategoryController', function($rootScope, flash, $state, $stateParams, $filter, CategoryFactory, md5) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Category");
        vm.index = function() {
            vm.loader = true;
            var params = {};
            params.categoryId = $stateParams.id;
            CategoryFactory.get(params, function(response) {
                vm.category = response.data;
                $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")(vm.category.name);
                if (angular.isDefined(vm.category.attachment) && vm.category.attachment !== null) {
                    vm.category.photo_url = '/images/big_thumb/Category/' + vm.category.attachment.id + '.' + md5.createHash('Category' + vm.category.attachment.id + 'png' + 'big_thumb') + '.png';
                } else {
                    vm.category.photo_url = '/images/no-image-950x350.png';
                }
                angular.forEach(vm.category.subcategories, function(subcategory) {
                    if (angular.isDefined(subcategory.attachment) && subcategory.attachment !== null) {
                        subcategory.photo_url = '/images/big_small_thumb/Category/' + subcategory.attachment.id + '.' + md5.createHash('Category' + subcategory.attachment.id + 'png' + 'big_small_thumb') + '.png';
                    } else {
                        subcategory.photo_url = '/images/no-image-184x176.png';
                    }
                });
                vm.loader = false;
            });
        };
        vm.index();
    });