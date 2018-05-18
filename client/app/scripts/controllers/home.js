'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:HomeController
 * @description
 * # HomeController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('HomeController', function($rootScope, $location, $window, $filter, $state, $timeout, StatsFactory) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Home");
        vm.index = function() {
            vm.getStats();
        };
        vm.getStats = function() {
            StatsFactory.get(function(response) {
                vm.stats = response;
            });
        };
        vm.index();
    });