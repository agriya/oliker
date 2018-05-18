'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:MainController
 * @description
 * # MainController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('MainController', function ($rootScope, $window, $cookies, md5, refreshToken, $location, $timeout, $uibModal, $uibModalStack, $state, CitiesFactory, $filter) {
        var vm = this;
        vm.site_url = $window.location.protocol + '//' + $window.location.host + '/ag-admin/#/dashboard';
        vm.isAuth = false;
        vm.cdate = new Date();
        if ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth"))) {
            vm.isAuth = true;
            $rootScope.user = angular.fromJson($cookies.get("auth"));
            if (angular.isDefined($rootScope.user.attachment) && $rootScope.user.attachment !== null) {
                var hash = md5.createHash('UserAvatar' + $rootScope.user.attachment.id + 'png' + 'small_thumb');
                vm.header_avatar_url = '/images/small_thumb/UserAvatar/' + $rootScope.user.attachment.id + '.' + hash + '.png';
            } else {
                vm.header_avatar_url = '/images/no_image_user_avatar.png';
            }
        }
        var unregisterUpdateParent = $rootScope.$on('updateParent', function (event, args) {
            if (args.isAuth === true) {
                vm.isAuth = true;
                if (angular.isDefined($rootScope.user.attachment) && $rootScope.user.attachment !== null) {
                    var hash = md5.createHash('UserAvatar' + $rootScope.user.attachment.id + 'png' + 'small_thumb');
                    vm.header_avatar_url = '/images/small_thumb/UserAvatar/' + $rootScope.user.attachment.id + '.' + hash + '.png';
                } else {
                    vm.header_avatar_url = '/images/no_image_user_avatar.png';
                }
            } else {
                vm.isAuth = false;
            }
        });
        var unregisterUseRefreshToken = $rootScope.$on('useRefreshToken', function (event, args) {
            //jshint unused:false
            if ($rootScope.refresh_token_loading !== true) {
                $rootScope.refresh_token_loading = true;
                var params = {};
                var auth = angular.fromJson($cookies.get("auth"));
                params.token = auth.refresh_token;
                refreshToken.get(params, function(response) {
                    if (angular.isDefined(response.access_token)) {

                        $cookies.remove("token", {
                            path: "/"
                        });
                        $rootScope.refresh_token_loading = false;
                        $timeout(function () {
                            $cookies.put('token', response.access_token, {
                                path: '/'
                            });
                            $window.location.reload();
                        }, 1000);
                    } else {
                        $cookies.remove("auth", {
                            path: "/"
                        });
                        $cookies.remove("token", {
                            path: "/"
                        });
                        var redirectto = $location.absUrl()
                            .split('/');
                        redirectto = redirectto[0] + '/users/login';
                        $rootScope.refresh_token_loading = false;
                        $window.location.href = redirectto;
                    }
                });
            }
        });
        $rootScope.$on("$destroy", function() {
            unregisterUpdateParent();
            unregisterUseRefreshToken();
        });
        vm.openLoginModal = function(tabactive, $redirect_url, $failed_url) {
            $cookies.put("header", $rootScope.header, {
                path: '/'
            });
            var redirect_url = "";
            var failed_url = "";
            if (angular.isDefined($redirect_url)) {
                redirect_url = $redirect_url;
                failed_url = $failed_url;
            } else {
                redirect_url = $location.url();
            }
            var current_state = $state.current.name;
            var exceptional_state = ['users_login', 'users_register'];
            if (exceptional_state.indexOf(current_state) === -1) {
                $cookies.put('redirect_url', redirect_url, {
                    path: '/'
                });
                $cookies.put('failed_url', failed_url, {
                    path: '/'
                });
                $state.go('users_login', {
                    param: ''
                }, {
                        notify: false
                    });
                vm.modalInstance = $uibModal.open({
                    templateUrl: 'views/login_modal.html',
                    backdrop: 'static',
                    controller: 'LoginInstanceModalController as vm',
                    resolve: {
                        tabactive: function () {
                            return tabactive;
                        }
                    }
                });
            } else {
                $location.path('/users/login');
            }
        };
        vm.cancel = function () {
            /*$rootScope.header = $cookies.get("header");
            if ($rootScope.previousState.state_name === 'ads') {
                $state.go($rootScope.previousState.state_name, {
                    category: $rootScope.previousState.params.category,
                    city: $rootScope.previousState.params.city,
                    page: $rootScope.previousState.params.page,
                }, {
                    notify: false
                });
            } else {
                $state.go($rootScope.previousState.state_name, {
                    id: $rootScope.previousState.params.id,
                    slug: $rootScope.previousState.params.slug,
                }, {
                    notify: false
                });
            }
            $uibModalStack.dismissAll();*/
            var redirect_url = $cookies.get("redirect_url");
            var failed_url = $cookies.get("failed_url");
            if (failed_url === '') {
                $location.path(redirect_url);
            } else {
                $location.path(failed_url);
            }
            $uibModalStack.dismissAll();
        };
        vm.switch_tab = function (tab) {
            if (tab === 'login') {
                $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Login");
                $state.go('users_login', {
                    param: ''
                }, {
                        notify: false
                    });
            } else {
                $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Register");
                $state.go('users_register', {
                    param: ''
                }, {
                        notify: false
                    });
            }
        };
        vm.selectCity = function (city) {
            vm.city = city.id;
            $cookies.put("city", angular.toJson({
                id: city.id,
                name: city.name
            }), {
                    path: '/'
                });
            vm.tmp_city = angular.fromJson($cookies.get("city"));
        };
        vm.getCityName = function (keyword) {
            var params = {};
            vm.cities_list = [];
            params.q = keyword;
            params.sort = 'name';
            params.sortby = "ASC";
            params.filter = "active";
            return CitiesFactory.get(params, function (response) {
                vm.cities = response.data;
                angular.forEach(vm.cities, function (value) {
                    vm.cities_list.push({
                        'id': value.id,
                        'name': value.address1,
                    });
                });
            });
        };
        vm.setCategory = function (category) {
            vm.category = category.id;
        };
        vm.getAds = function (q) {
            vm.q = q;
            $state.go('ads', {
                q: vm.q,
                city_id: vm.city,
                category_id: vm.category
            }, {
                    reload: true
                });
        };
        vm.index = function () {
            if (angular.isDefined($cookies.get("city")) && $cookies.get("city") !== null) {
                vm.tmp_city = angular.fromJson($cookies.get("city"));
                vm.city_name = {};
                vm.city_name.selected = {
                    id: vm.tmp_city.id,
                    name: vm.tmp_city.name,
                };
            }
            if (angular.isDefined($cookies.get("category")) && $cookies.get("category") !== null) {
                vm.tmp_category = angular.fromJson($cookies.get("category"));
                vm.category_name = {};
                vm.category_name.selected = {
                    id: vm.tmp_category.id,
                    name: vm.tmp_category.name,
                };
            }
        };
        vm.index();
    })
    .controller('LoginInstanceModalController', function ($uibModalStack, $rootScope, $state, $filter, tabactive) {
        var vm = this;
        if (tabactive === 'login') {
            vm.loginactive = 0;
        } else {
            vm.loginactive = 1;
            $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Register");
            $state.go('users_register', {
                param: ''
            }, {
                    notify: false
                });
        }
    });