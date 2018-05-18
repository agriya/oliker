/*globals $:false */
'use strict';
/**
 * @ngdoc service
 * @name olikerApp.ScrollPageFactory
 * @description
 * # ScrollPageFactory
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('ScrollPageFactory', function() {
        return {
            scrollPageTop: function() {
                $('html, body')
                    .stop(true, true)
                    .animate({
                        scrollTop: 0
                    }, 600);
            }
        };
    });