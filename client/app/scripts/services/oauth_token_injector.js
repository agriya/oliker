'use strict';
/**
 * @ngdoc service
 * @name olikerApp.oauthTokenInjector
 * @description
 * # sessionService
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('OauthTokenInjectorFactory', function($cookies) {
        var oauthTokenInjector = {
            request: function(config) {
                if (config.url.indexOf('.html') === -1) {
                    if ($cookies.get("token") !== null && angular.isDefined($cookies.get("token"))) {
                        var sep = config.url.indexOf('?') === -1 ? '?' : '&';
                        config.url = config.url + sep + 'token=' + $cookies.get("token");
                    }
                }
                return config;
            }
        };
        return oauthTokenInjector;
    });