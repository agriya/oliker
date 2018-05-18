angular.module('base')
    .factory('interceptor', ['$q', '$location', '$injector', '$window', '$rootScope', '$timeout', '$cookies', function($q, $location, $injector, $window, $rootScope, $timeout, $cookies) {
        return {
            // On response success
            response: function(response) {
                if (angular.isDefined(response.data)) {
                    if (angular.isDefined(response.data.error)) {
                        if (parseInt(response.data.error.code) === 1 && (response.data.error.message === 'Authentication failed' || response.data.error.message === 'Authorization Failed')) {
                            if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
                                var auth = JSON.parse($cookies.get("auth"));
                                var refresh_token = auth.refresh_token;
                                if (refresh_token === null || refresh_token === '' || refresh_token === undefined) {
                                    $cookies.remove('auth', {
                                        path: '/'
                                    });
                                    $cookies.remove('token', {
                                        path: '/'
                                    });
                                    $location.path('/users/login');
                                    $rootScope.refresh_token_loading = false;
                                    window.location.href = redirectto;
                                } else {
                                    if ($rootScope.refresh_token_loading !== true) {
                                        //jshint unused:false
                                        $rootScope.refresh_token_loading = true;
                                        var params = {};
                                        auth = JSON.parse($cookies.get("auth"));
                                        params.token = auth.refresh_token;
                                        var refreshToken = $injector.get('refreshToken');
                                        refreshToken.get(params, function(response) {
                                            if (angular.isDefined(response.access_token)) {
                                                $rootScope.refresh_token_loading = false;
                                                $cookies.put('token', response.access_token, {
                                                    path: '/'
                                                });
                                            } else {
                                                $cookies.remove('auth', {
                                                    path: '/'
                                                });
                                                $cookies.remove('token', {
                                                    path: '/'
                                                });
                                                $location.path('/users/login');
                                                /*  var redirectto = $location.absUrl()
                                                      .split('/#/');
                                                  redirectto = redirectto[0] + '/users/login';*/
                                                $rootScope.refresh_token_loading = false;
                                                window.location.href = redirectto;
                                            }
                                            $timeout(function() {
                                                $window.location.reload();
                                            }, 1000);
                                        });
                                    }
                                }
                            }
                        }
                    }
                }
                // Return the response or promise.
                return response || $q.when(response);
            },
            // On response failture
            responseError: function(response) {
                if (angular.isDefined(response.data.error)) {
                    if (parseInt(response.data.error.code) === 1 && (response.data.error.message === 'Authentication failed' || response.data.error.message === 'Authorization Failed')) {
                        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
                            var auth = JSON.parse($cookies.get("auth")),
                                refresh_token = auth.refresh_token;
                            if (refresh_token === null || refresh_token === '' || refresh_token === undefined) {
                                $cookies.remove('auth', {
                                    path: '/'
                                });
                                $cookies.remove('token', {
                                    path: '/'
                                });
                                $location.path('/users/login');
                                window.location.href = redirectto;
                            } else {
                                if ($rootScope.refresh_token_loading !== true) {
                                    //jshint unused:false
                                    $rootScope.refresh_token_loading = true;
                                    var params = {};
                                    auth = JSON.parse($cookies.get("auth"));
                                    params.token = auth.refresh_token;
                                    var refreshToken = $injector.get('refreshToken');
                                    refreshToken.get(params, function(response) {
                                        if (angular.isDefined(response.access_token)) {
                                            $rootScope.refresh_token_loading = false;
                                            $cookies.put('token', response.access_token, {
                                                path: '/'
                                            });
                                        } else {
                                            $cookies.remove('auth', {
                                                path: '/'
                                            });
                                            $cookies.remove('token', {
                                                path: '/'
                                            });
                                            $location.path('/users/login');
                                            /*  var redirectto = $location.absUrl()
                                                  .split('/#/');
                                              redirectto = redirectto[0] + '/users/login';*/
                                            $rootScope.refresh_token_loading = false;
                                            window.location.href = redirectto;
                                        }
                                        $timeout(function() {
                                            $window.location.reload();
                                        }, 1000);
                                    });
                                }
                            }
                        }
                    }
                }
                // Return the promise rejection.
                return $q.reject(response);
            },
            request: function(config) {
                var exceptional_array = ['/api/v1/stats', '/api/v1/settings', '/api/v1/users/logout', '/api/v1/oauth/refresh_token'];
                if ($cookies.get('auth') !== null && $cookies.get('auth') !== undefined) {
                    var auth = angular.fromJson($cookies.get('auth'));
                }
                if (/\/user_cash_withdrawals$/.test(config.url) && !config.params && config.method !== 'POST') {
                    config.url += '/2';
                }
                return config;
            },
        };
    }]);