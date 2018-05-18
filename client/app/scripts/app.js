/*globals $:false */
'use strict';
/**
 * @ngdoc overview
 * @name olikerApp
 * @description
 * # olikerApp
 *
 * Main module of the application.
 */
angular.module('olikerApp', [
    'olikerApp.Ad',
    'olikerApp.Ad.AdExtra',
    'olikerApp.Common.Message',
    'olikerApp.Ad.AdReport',
    'olikerApp.Ad.AdFavorite',
    'olikerApp.Common.Wallet',
    'olikerApp.Common.Withdrawal',
    'olikerApp.Ad.AdPackage',
    'olikerApp.Common.ZazPay',
    'olikerApp.Common.Paypal',
    'ngResource',
    'ngSanitize',
    'satellizer',
    'ngAnimate',
    'ui.bootstrap',
    'ui.bootstrap.datetimepicker',
    'ui.router',
    'angular-growl',
    'google.places',
    'angular.filter',
    'ngCookies',
    'angular-md5',
    'http-auth-interceptor',
    'vcRecaptcha',
    'angulartics',
    'pascalprecht.translate',
    'angulartics.google.analytics',
    'tmh.dynamicLocale',
    'ngMap',
    'chieffancypants.loadingBar',
    'payment',
    'ui.select',
    'builder',
    'builder.components',
    'validator.rules',
    'ngFileUpload',
    'angularMoment',
    'swipe',
    'rzModule',
    'oitozero.ngSweetAlert',
    'angular-flexslider'
])
    .config(function ($stateProvider, $urlRouterProvider, $translateProvider) {
        //$translateProvider.translations('en', translations).preferredLanguage('en');
        $translateProvider.useStaticFilesLoader({
            prefix: 'scripts/l10n/',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('en');
        $translateProvider.useLocalStorage(); // saves selected language to localStorage
        // Enable escaping of HTML
        $translateProvider.useSanitizeValueStrategy('escape');
        //	$translateProvider.useCookieStorage();
    })
    .config(function (tmhDynamicLocaleProvider) {
        tmhDynamicLocaleProvider.localeLocationPattern('scripts/l10n/angular-i18n/angular-locale_{{locale}}.js');
    })
    .config(function ($authProvider, $windowProvider) {
        var $window = $windowProvider.$get();
        var params = {};
        params.fields = 'api_key,slug';
        $.get('/api/v1/providers', params, function (response) {
            var credentials = {};
            var url = '';
            var providers = response;
            angular.forEach(providers.data, function (res, i) {
                //jshint unused:false
                url = $window.location.protocol + '//' + $window.location.host + '/api/v1/users/social_login?type=' + res.slug;
                credentials = {
                    clientId: res.api_key,
                    redirectUri: url,
                    url: url
                };
                if (res.slug === 'facebook') {
                    $authProvider.facebook(credentials);
                }
                if (res.slug === 'google') {
                    $authProvider.google(credentials);
                }
                if (res.slug === 'twitter') {
                    $authProvider.twitter(credentials);
                }
            });
        });
    })
    .config(function ($locationProvider) {
        //$locationProvider.html5Mode(false);
        //$locationProvider.hashPrefix('!');
        $locationProvider.html5Mode(true);
    })
    .config(function ($stateProvider, $urlRouterProvider) {
        var getToken = {
            'TokenServiceData': function (TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
        };
        $urlRouterProvider.otherwise('/');
        $stateProvider.state('home', {
            url: '/',
            templateUrl: 'views/home.html',
            controller: 'HomeController as vm',
            resolve: getToken
        })
            .state('users_settings', {
                url: '/users/settings',
                templateUrl: 'views/users_settings.html',
                resolve: getToken
            })
            .state('users_change_password', {
                url: '/users/change_password',
                templateUrl: 'views/users_change_password.html',
                resolve: getToken
            })
            .state('users_login', {
                url: '/users/login',
                templateUrl: 'views/users_login.html',
                resolve: getToken
            })
            .state('users_register', {
                url: '/users/register',
                templateUrl: 'views/users_register.html',
                resolve: getToken
            })
            .state('users_logout', {
                url: '/users/logout',
                controller: 'UsersLogoutController as vm',
                resolve: getToken
            })
            .state('users_forgot_password', {
                url: '/users/forgot_password',
                templateUrl: 'views/users_forgot_password.html',
                resolve: getToken
            })
            .state('contact', {
                url: '/contact',
                templateUrl: 'views/contact.html',
                resolve: getToken
            })
            .state('pages_view', {
                url: '/pages/:id/:slug',
                templateUrl: 'views/pages_view.html',
                resolve: getToken
            })
            .state('users_activation', {
                url: '/users/activation/:user_id/:hash',
                templateUrl: 'views/users_activation.html',
                resolve: getToken
            })
            .state('user_notification', {
                url: '/user/notification',
                templateUrl: 'views/user_notification.html',
                resolve: getToken
            })
            .state('transactions', {
                url: '/transactions',
                templateUrl: 'views/transactions.html',
                resolve: getToken
            })
            .state('get_email', {
                url: '/users/get_email',
                templateUrl: 'views/get_email.html',
                resolve: getToken
            })
            .state('how_it_works', {
                url: '/how_it_works',
                templateUrl: 'views/how_it_works.html',
                resolve: getToken
            });
    })
    .config(function (growlProvider) {
        growlProvider.onlyUniqueMessages(true);
        growlProvider.globalTimeToLive(5000);
        growlProvider.globalPosition('top-center');
        growlProvider.globalDisableCountDown(true);
    })
    .run(function ($rootScope, $location, $window, $cookies) {
        var unregisterStateChangeStart = $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
            //jshint unused:false
            $rootScope.previousState = {};
            $rootScope.previousState.state_name = toState.name;
            $rootScope.previousState.params = toParams;
            var url = toState.name;
            var exception_arr = ['home', 'users_login', 'users_register', 'users_forgot_password', 'ads', 'ad_view', 'pages_view', 'contact', 'category', 'get_email', 'users_activation', 'how_it_works'];
            if (angular.isDefined(url)) {
                if (exception_arr.indexOf(url) === -1 && angular.isUndefined($cookies.get("auth"))) {
                    $location.path('/users/login');
                }
            }
        });
        var unregisterViewContentLoaded = $rootScope.$on('$viewContentLoaded', function () {
            angular.element('div.loader')
                .hide();
            angular.element('body')
                .removeClass('site-loading');
        });
        var unregisterStateChangeSuccess = $rootScope.$on('$stateChangeSuccess', function () {
            angular.element('html, body')
                .stop(true, true)
                .animate({
                    scrollTop: 0
                }, 600);
        });
        $rootScope.$on("$destroy", function () {
            unregisterStateChangeStart();
            unregisterViewContentLoaded();
            unregisterStateChangeSuccess();
        });
    })
    .config(function ($httpProvider) {
        $httpProvider.interceptors.push('interceptor');
        $httpProvider.interceptors.push('OauthTokenInjectorFactory');
    })
    .config(function (cfpLoadingBarProvider) {
        // true is the default, but I left this here as an example:
        cfpLoadingBarProvider.includeSpinner = false;
    })
    .factory('interceptor', function ($q, $location, flash, $window, $timeout, $rootScope, $filter, $cookies) {
        return {
            // On response success
            response: function (response) {
                if (angular.isDefined(response.data)) {
                    if (angular.isDefined(response.data.thrid_party_login)) {
                        if (angular.isDefined(response.data.error)) {
                            if (angular.isDefined(response.data.error.code) && parseInt(response.data.error.code) === 0) {
                                $cookies.put('auth', angular.toJson(response.data.user), {
                                    path: '/'
                                });
                                $timeout(function () {
                                    location.reload(true);
                                });
                            } else {
                                var flashMessage;
                                flashMessage = $filter("translate")("Unable to connect your account.");
                                flash.set(flashMessage, 'error', false);
                            }
                        }
                    }
                }
                // Return the response or promise.
                return response || $q.when(response);
            },
            // On response failture
            responseError: function (response) {
                // Return the promise rejection.
                if (response.status === 401) {
                    if ($cookies.get("auth") !== null && angular.isDefined($cookies.get("auth")) ) {
                        var auth = angular.fromJson($cookies.get("auth"));
                        var refresh_token = auth.refresh_token;
                        if (refresh_token === null || refresh_token === ''|| angular.isUndefined(refresh_token)) {
                            $cookies.remove('auth', {
                                path: '/'
                            });
                            $cookies.remove('token', {
                                path: '/'
                            });
                            var redirectto = $location.absUrl()
                                .split('/');
                            redirectto = redirectto[0] + '/users/login';
                            $rootScope.refresh_token_loading = false;
                            $window.location.href = redirectto;
                        } else {
                            if ($rootScope.refresh_token_loading !== true) {
                                $rootScope.$broadcast('useRefreshToken');
                            }
                        }
                    } else {
                        $cookies.remove('auth', {
                            path: '/'
                        });
                        $cookies.remove('token', {
                            path: '/'
                        });
                        $location.path('/users/login');
                    }
                }
                return $q.reject(response);
            }
        };
    })
    .filter('unsafe', function ($sce) {
        return function (val) {
            return $sce.trustAsHtml(val);
        };
    })
    .filter('split', function () {
        return function (input, splitChar) {
            var _input = input.split(splitChar);
            _input.pop();
            return _input.join(':');
        };
    })
    .filter('spaceless', function () {
        return function (input) {
            if (input) {
                return input.replace(/\s+/g, '-');
            }
        };
    });