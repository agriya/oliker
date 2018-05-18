'use strict';
/**
 * @ngdoc function
 * @name sheermeApp.controller:CategoryController
 * @description
 * # CategoryController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('CategorySelectController', function($rootScope, flash, $state, $cookies, $stateParams, $filter, CategoriesFactory, md5) {
        var vm = this;
        vm.getCategories = function(keyword) {
            var params = {};
            params.q = keyword;
            params.sort = 'name';
            params.sortby = "ASC";
            params.limit = "all";
            params.parent_id = 0;
            return CategoriesFactory.get(params, function(response) {
                vm.categories = response.data;
            });
        };
    });