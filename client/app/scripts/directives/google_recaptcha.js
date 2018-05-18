'use strict';
/**
 * @ngdoc directive
 * @name olikerApp.directive:googleRecaptcha
 * @description
 * # googleRecaptcha
 */
angular.module('olikerApp')
    .directive('googleRecaptcha', function() {
        return {
            restrict: 'C',
            scope: '=',
            template: '<div vc-recaptcha theme="\'light\'" key="model.key" on-create="setWidgetId(widgetId)" on-success="setResponse(response)" on-expire="cbExpiration()"></div>',
            controller: function($rootScope, $scope, vcRecaptchaService) {
                $scope.model = {
                    key: $rootScope.settings.GOOGLE_RECAPTCHA_CODE
                };
                $scope.setResponse = function(response) {
                    $scope.response = response;
                };
                $scope.setWidgetId = function(widgetId) {
                    $scope.widgetId = widgetId;
                };
                $scope.cbExpiration = function() {
                    vcRecaptchaService.reload($scope.widgetId);
                    $scope.response = null;
                };
            },
        };
    });