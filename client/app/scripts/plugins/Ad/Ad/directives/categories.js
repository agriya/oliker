'use strict';
/**
 * @ngdoc service
 * @name olikerApp.categories
 * @description
 * # categories
 * Directive in the olikerApp.
 */
angular.module('olikerApp.Ad')
    .directive('categoryList', function() {
        return {
            templateUrl: 'scripts/plugins/Ad/Ad/views/default/category_list.html',
            restrict: 'EA',
            replace: true,
            scope: true,
            controller: 'CategoryListController',
            controllerAs: 'vm',
            link: function postLink(scope, element, attr) {}
        };
    })
    .directive('categoryExplore', function() {
        return {
            templateUrl: 'scripts/plugins/Ad/Ad/views/default/category_explore.html',
            restrict: 'EA',
            replace: true,
            scope: true,
            controller: 'CategoryListController',
            controllerAs: 'vm',
            link: function postLink(scope, element, attr) {}
        };
    })
    .directive('categorySelect', function(AdFavoritesFactory, $cookies, AdFavoriteFactory) {
        return {
            templateUrl: 'scripts/plugins/Ad/Ad/views/default/category_select.html',
            restrict: 'EA',
            replace: true,
            scope: true,
            bindToController: {
                callback: '&'
            },
            controller: 'CategorySelectController',
            controllerAs: 'vm',
            link: function postLink(scope, element, attr, vm) {
                vm.selectCategory = function(category) {
                    vm.category = category.id;
                    $cookies.put("category", angular.toJson({
                        id: category.id,
                        name: category.name
                    }), {
                        path: '/'
                    });
                    vm.tmp_category = angular.fromJson($cookies.get("category"));
                    vm.callback({
                        $value: vm.tmp_category
                    });
                };
            }
        };
    });