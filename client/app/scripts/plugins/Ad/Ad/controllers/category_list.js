'use strict';
/**
 * @ngdoc function
 * @name sheermeApp.controller:CategoryController
 * @description
 * # CategoryController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('CategoryListController', function($rootScope, flash, $state, $stateParams, $filter, CategoriesFactory, md5) {
        var vm = this;
        vm.index = function() {
            vm.getCategories();
        };
        vm.getCategories = function() {
            var params = {};
            params.limit = 12;
            params.is_popular = true;
            CategoriesFactory.get(params, function(response) {
                vm.categories = response.data;
                angular.forEach(vm.categories, function(category, key) {
                    if (angular.isDefined(category.subcategories)) {
                        angular.forEach(category.subcategories, function(subcategory, sub_key) {
                            if (angular.isDefined(subcategory.attachment) && subcategory.attachment !== null) {
                                var hash = md5.createHash('Category' + subcategory.attachment.id + 'png' + 'medium_thumb');
                                vm.categories[key].subcategories[sub_key].image_name = '/images/medium_thumb/Category/' + subcategory.attachment.id + '.' + hash + '.png';
                            } else {
                                vm.categories[key].subcategories[sub_key].image_name = '/images/no-image-184x176.png';
                            }
                        });
                    }
                    if (angular.isDefined(category.attachment) && category.attachment !== null) {
                        var hash = md5.createHash('Category' + category.attachment.id + 'png' + 'small_thumb');
                        vm.categories[key].image_name = '/images/small_thumb/Category/' + category.attachment.id + '.' + hash + '.png';
                    } else {
                        vm.categories[key].image_name = '/images/no-image-47x37.png';
                    }
                });
            });
        };
        vm.index();
    });