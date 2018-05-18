'use strict';
/**
 * @ngdoc directive
 * @name olikerApp.directive:googleAnalytics
 * @description
 * # googleAnalytics
 */
angular.module('olikerApp')
    .directive('googleAnalytics', function() {
        return {
            restrict: 'AE',
            replace: true,
            template: '<div ng-bind-html="googleAnalyticsCode | unsafe"></div>',
            controller: function($rootScope, $scope, TokenService) {
                //jshint unused:false
                var promise = TokenService.promise;
                var promiseSettings = TokenService.promiseSettings;
                promiseSettings.then(function(data) {
                    if ($rootScope.settings) {
                        $scope.googleAnalyticsCode = $rootScope.settings.SITE_TRACKING_SCRIPT;
                    }
                });
            },
            scope: {}
        };
    });