'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersLogutController
 * @description
 * # UsersLogutController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UsersLogoutController', function($rootScope, $scope, UserLogoutFactory, $location, flash, $filter, $cookies) {
        var vm = this;
        UserLogoutFactory.logout('', function(response) {
            vm.response = response;
            if (vm.response.error.code === 0) {
                flash.set($filter("translate")("Logout Successfully"), 'success', false);
                delete $rootScope.user;
                $cookies.remove("auth", {
                    path: "/"
                });
                $cookies.remove("token", {
                    path: "/"
                });
                $scope.$emit('updateParent', {
                    isAuth: false
                });
                $location.path('/');
            }
        });
    });