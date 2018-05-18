'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:BannerController
 * @description
 * # BannerController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('BannerController', function($rootScope, $scope, SettingsFactory, $element, $attrs) {
        var vm = this;
        var baner_position = $attrs.position;
        vm.target_position = baner_position;
        vm.index = function() {
            var params = {};
            params.fields = 'name,value';
            params.limit = 'all';
            SettingsFactory.get(params, function(response) {
                angular.forEach(response.data, function(setting_value) {
                    if (setting_value.name === 'WIDGET_FOOTER_SCRIPT') {
                        vm.footer_banner = setting_value.value;
                    }
                });
            });
        };
        vm.index();
    });