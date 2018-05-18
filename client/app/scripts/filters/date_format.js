'use strict';
/**
 * @ngdoc filter
 * @name olikerApp.filter:dateFormat
 * @function
 * @description
 * # dateFormat
 * Filter in the olikerApp.
 */
angular.module('olikerApp')
    .filter('medium', function myDateFormat($filter) {
        return function(text) {
            var tempdate = new Date(text.replace(/(.+) (.+)/, "$1T$2Z"));
            return $filter('date')(tempdate, "medium");
        };
    });