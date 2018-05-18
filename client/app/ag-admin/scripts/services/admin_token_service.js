'use strict';
/**
 * @ngdoc service
 * @name olikerApp.sessionService
 * @description
 * # sessionService
 * Factory in the olikerApp.
 */
angular.module('base')
    .service('adminTokenService', function($rootScope, $http, $window, $q, $cookies) {
        //jshint unused:false
        var promise;
        var promiseSettings;
        var deferred = $q.defer();
        if ($cookies.get("token") === null || $cookies.get("token") === undefined) {
            promise = $http({
                    method: 'GET',
                    url: '/api/v1/oauth/token',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                .success(function(data) {
                    if (angular.isDefined(data.access_token)) {
                        $cookies.put("token", data.access_token, {
                            path: '/'
                        });
                    }
                });
        } else {
            promise = true;
        }
        if (angular.isUndefined($rootScope.settings)) {
            $rootScope.settings = {};
            var params = {};
            params.fields = 'name,value';
            params.limit = 'all';
            params.sortby = 'asc';
            promiseSettings = $http({
                    method: 'GET',
                    url: '/api/v1/settings',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    params: params
                })
                .success(function(response) {
                    if (angular.isDefined(response.data)) {
                        var settings = {};
                        angular.forEach(response.data, function(value, key) {
                            //jshint unused:false
                            $rootScope.settings[value.name] = value.value;
                            settings[value.name] = value.value;
                        });
                        if ($cookies.get("SETTINGS") === null || $cookies.get("SETTINGS") === undefined) {
                            $cookies.put("SETTINGS", JSON.stringify(settings), {
                                path: '/'
                            });
                        }
                    }
                });
        } else {
            promiseSettings = true;
        }
        return {
            promise: promise,
            promiseSettings: promiseSettings
        };
    });