/*jshint sub:true*/
'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:SearchCtrl
 * @description
 * # SearchCtrl
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .factory('languageList', function() {
        /*jshint -W117 */
        var promise = $.get('/api/v1/languages?filter=active&sort=name&sortby=asc&limit=all', function() {});
        return {
            promise: promise
        };
    })
    .factory('LocaleService', function($translate, $rootScope, tmhDynamicLocale, languageList, $cookies, $document) {
        /*jshint -W117 */
        var localesObj;
        var localesObj1 = {};
        localesObj1.locales = {};
        localesObj1.preferredLocale = {};
        var _LOCALES_DISPLAY_NAMES = [];
        var _LOCALES;
        var promiseSettings = languageList.promise;
        promiseSettings.then(function(response) {
            $.each(response.data, function(i, data) {
                localesObj1.locales[data.iso2] = data.name;
            });
            localesObj1.preferredLocale = response.data[0].iso2;
            localesObj = localesObj1.locales;
            _LOCALES = Object.keys(localesObj);
            if (!_LOCALES || _LOCALES.length === 0) {
                $log.error('There are no _LOCALES provided');
            }
            _LOCALES.forEach(function(locale) {
                _LOCALES_DISPLAY_NAMES.push(localesObj[locale]);
            });
        });
        var currentLocale;
        if (angular.isDefined($cookies.get("currentLocale"))) {
            currentLocale = $cookies.get("currentLocale");
            $translate.use(currentLocale);
        } else if (angular.isDefined($rootScope.settings['SITE_LANGUAGE']) && $rootScope.settings['SITE_LANGUAGE'] !== '') {
            currentLocale = $rootScope.settings['SITE_LANGUAGE'];
            $cookies.put('currentLocale', currentLocale, {
                path: '/'
            });
            $translate.use(currentLocale);
        } else {
            currentLocale = $translate.use() || $translate.preferredLanguage(); // because of async loading
            $cookies.put('currentLocale', currentLocale, {
                path: '/'
            });
            $translate.use(currentLocale);
        }
        // var currentLocale = $translate.use() || $translate.preferredLanguage(); // because of async loading
        // $cookies.put('currentLocale', currentLocale, {
        //     path: '/'
        // });
        var checkLocaleIsValid = function(locale) {
            return _LOCALES.indexOf(locale) !== -1;
        };
        var setLocale = function(locale) {
            if (!checkLocaleIsValid(locale)) {
                $log.error('Locale name "' + locale + '" is invalid');
                return;
            }
            currentLocale = locale;
            $cookies.put('currentLocale', currentLocale, {
                path: '/'
            });
            $translate.use(locale);
        };
        var unregisterTranslateChangeSuccess = $rootScope.$on('$translateChangeSuccess', function(event, data) {
            $document[0].documentElement.setAttribute('lang', data.language);
            $rootScope.$emit('changeLanguage', {
                currentLocale: data.language,
            });
            tmhDynamicLocale.set(data.language.toLowerCase()
                .replace(/_/g, '-'));
        });
        $rootScope.$on("$destroy", function() {
            unregisterTranslateChangeSuccess();
        });
        return {
            getLocaleDisplayName: function() {
                if (angular.isDefined(localesObj)) {
                    return localesObj[currentLocale];
                }
            },
            setLocaleByDisplayName: function(localeDisplayName) {
                setLocale(_LOCALES[_LOCALES_DISPLAY_NAMES.indexOf(localeDisplayName)]);
            },
            getLocalesDisplayNames: function() {
                return _LOCALES_DISPLAY_NAMES;
            }
        };
    })
    .directive('ngTranslateLanguageSelect', function(LocaleService) {
        return {
            restrict: 'AE',
            templateUrl: 'views/language_translate.html',
            controller: function($scope, $rootScope, $timeout, languageList) {
                var promiseSettings = languageList.promise;
                promiseSettings.then(function() {
                    $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                    $scope.localesDisplayNames = LocaleService.getLocalesDisplayNames();
                    $scope.visible = $scope.localesDisplayNames && $scope.localesDisplayNames.length > 1;
                });
                $scope.changeLanguage = function(locale) {
                    LocaleService.setLocaleByDisplayName(locale);
                    $scope.currentLocaleDisplayName = LocaleService.getLocaleDisplayName();
                };
            }
        };
    });