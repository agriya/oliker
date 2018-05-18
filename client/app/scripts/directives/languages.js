'use strict';
/**
 * @ngdoc directive
 * @name olikerApp.directive:languages
 * @description
 * # languages
 */
angular.module('olikerApp')
    .directive('languages', function() {
        return {
            templateUrl: 'views/languages.html',
            restrict: 'AE',
            link: function postLink(scope, element, attrs) {
                //jshint unused:false
            },
            controller: function(LocaleService, $scope) {
                $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                $scope.localesDisplayNames = LocaleService.getLocalesDisplayNames();
                $scope.visible = $scope.localesDisplayNames && $scope.localesDisplayNames.length > 1;
                $scope.changeLanguage = function(locale) {
                    LocaleService.setLocaleByDisplayName(locale);
                    $scope.currentLocaleDisplayName = locale;
                };
            }
        };
    });