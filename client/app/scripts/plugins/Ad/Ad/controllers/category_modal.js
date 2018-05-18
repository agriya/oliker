'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:MyAdsListController
 * @description
 * # MyAdsListController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('CategoryModalInstaceController', function($rootScope, $state, $uibModalStack, $uibModal, categories, level, max) {
        var vm = this;
        vm.level = level;
        vm.group_categories = categories;
        vm.init = function() {
            for (var i = 0; i <= max; i++) {
                if (i !== 0) {
                    vm.group_categories[i] = [];
                }
            }
        };
        vm.setCategies = function(main_key, parent_id, child_key, category) {
            vm.selected_category = category;
            if (angular.isDefined(vm.group_categories[main_key][child_key].subcategories)) {
                if (vm.group_categories[main_key][child_key].subcategories.length > 0) {
                    var i = main_key + 1;
                    var next_level = i + 1;
                    for (var j = next_level; j <= max; j++) {
                        vm.group_categories[j] = [];
                    }
                    vm.group_categories[i] = vm.group_categories[main_key][child_key].subcategories;
                } else {
                    var category_chosen = vm.group_categories[main_key][child_key];
                    $rootScope.$emit('updateCategory', {
                        category: category_chosen
                    });
                    $uibModalStack.dismissAll();
                }
            }
        };
        vm.cancel = function() {
            $uibModalStack.dismissAll();
            $state.reload();
        };
        vm.init();
    });