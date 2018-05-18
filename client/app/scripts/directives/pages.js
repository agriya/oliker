'use strict';
/**
 * @ngdoc directive
 * @name olikerApp.directive:pages
 * @description
 * # pages
 */
angular.module('olikerApp')
    .directive('pages', function(PagesFactory) {
        return {
            templateUrl: 'views/pages.html',
            restrict: 'E',
            replace: 'true',
            link: function postLink(scope, element, attrs) {
                //jshint unused:false
                var params = {
                    limit: 20,
                    is_active: true
                };
                PagesFactory.get(params, function(response) {
                    if (angular.isDefined(response.data)) {
                        scope.pages = response.data;
                    }
                });
            }
        };
    });