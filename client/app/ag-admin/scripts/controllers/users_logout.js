'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersLoginCtrl
 * @description
 * # UsersLoginCtrl
 * Controller of the olikerApp
 */
angular.module('base')
    .controller('UsersLogoutCtrl', function($scope, $location, $http, $window, $timeout, $cookies) {
        $http({
                method: 'GET',
                url: '/api/v1/users/logout'
            })
            .success(function(response) {
                $scope.response = response;
                if ($scope.response.error.code === 0) {
                    $cookies.remove('auth', {
                        path: '/'
                    });
                    $cookies.remove('token', {
                        path: '/'
                    });
                    $location.path('/users/login');
                    $timeout(function() {
                        $window.location.reload();
                    }, 50);
                }
            });
    });