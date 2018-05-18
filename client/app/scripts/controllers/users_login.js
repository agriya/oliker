'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersLoginController
 * @description
 * # UsersLoginController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UsersLoginController', function($rootScope, UserLoginFactory, ProviderFactory, $auth, flash, $window, $location, $filter, $cookies, $state, $uibModalStack, $timeout, $log) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Login");
        vm.init = function() {
            $timeout(function() {
                $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Login");
            }, 100);
        };
        if ($cookies.get('auth') !== null && angular.isDefined($cookies.get('auth'))) {
            $rootScope.$emit('updateParent', {
                isAuth: true
            });
            $rootScope.header = $rootScope.settings.SITE_NAME + ' | Home';
            $location.path('/');
        }
        vm.save_btn = false;
        vm.save = function() {
            if (vm.userLogin.$valid && !vm.save_btn) {
                vm.save_btn = true;
                if ($rootScope.settings.USER_USING_TO_LOGIN === 'email') {
                    vm.user.email = vm.user.username;
                    delete vm.user.username;
                }
                UserLoginFactory.login(vm.user, function(response) {
                    vm.response = response;
                    delete vm.response.scope;
                    if (vm.response.error.code === 0) {
                        flash.set($filter("translate")("Login Successfully"), 'success', false);
                        $cookies.put('auth', angular.toJson(vm.response), {
                            path: '/'
                        });
                        $cookies.put('token', vm.response.access_token, {
                            path: '/'
                        });
                        $rootScope.user = vm.response;
                        $rootScope.$broadcast('updateParent', {
                            isAuth: true
                        });
                        if ($cookies.get("redirect_url") !== null && angular.isDefined($cookies.get("redirect_url"))) {
                            $uibModalStack.dismissAll();
                            $location.path($cookies.get("redirect_url"));
                            $cookies.remove("redirect_url", {
                                path: "/"
                            });
                        } else {
                            $uibModalStack.dismissAll();
                            $location.path('/users/settings');
                        }
                    } else {
                        flash.set($filter("translate")("Sorry, login failed. Either your username or password are incorrect or admin deactivated your account."), 'error', false);
                        vm.save_btn = false;
                    }
                });
            }
        };
        /*vm.authenticate = function(provider) {
            $auth.authenticate(provider);
        };*/
        vm.authenticate = function(provider) {
            $auth.authenticate(provider)
                .then(function(response) {
                    vm.response = response.data;
                    delete vm.response.scope;
                    //Twitter login
                    if (vm.response.error.code === 0 && vm.response.thrid_party_profile) {
                        $window.localStorage.setItem("twitter_auth", angular.toJson(vm.response));
                        $state.go('get_email');
                    } else if (vm.response.access_token) {
                        $cookies.put('auth', angular.toJson(vm.response.user), {
                            path: '/'
                        });
                        $cookies.put('token', vm.response.access_token, {
                            path: '/'
                        });
                        $rootScope.user = vm.response.user;
                        $rootScope.$emit('updateParent', {
                            isAuth: true
                        });
                        if ($cookies.get("redirect_url") !== null && angular.isDefined($cookies.get("redirect_url"))) {
                            $cookies.remove('redirect_url');
                            $location.path($cookies.get("redirect_url"));
                        } else {
                            $location.path('/');
                        }
                    }
                    $uibModalStack.dismissAll();
                })
                .catch(function(error) {
                    $log.log("error in login", error);
                });
        };
        var params = {};
        params.fields = 'name,icon_class,slug,button_class';
        params.is_active = true;
        ProviderFactory.get(params, function(response) {
            vm.providers = response.data;
        });
        vm.init();
    })
    .controller('TwitterLoginController', function($rootScope, TwitterLoginFactory, ProviderFactory, $auth, flash, $window, $location, $state, $cookies) {
        var vm = this;
        if ($window.localStorage.getItem("twitter_auth") !== null) {
            vm.user = angular.fromJson($window.localStorage.getItem("twitter_auth"));
            vm.loginNow = function($valid) {
                if ($valid) {
                    $window.localStorage.removeItem("twitter_auth");
                    TwitterLoginFactory.login(vm.user, function(response) {
                        vm.response = response;
                        if (vm.response.access_token) {
                            $cookies.put('auth', angular.toJson(vm.response), {
                                path: '/'
                            });
                            $cookies.put('token', vm.response.access_token, {
                                path: '/'
                            });
                            $rootScope.user = vm.response.user;
                            $rootScope.$emit('updateParent', {
                                isAuth: true
                            });
                            $state.go('home');
                        }
                    });
                }
            };
        } else {
            $location.path('/users/login');
        }
    });