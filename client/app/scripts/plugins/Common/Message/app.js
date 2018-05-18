'use strict';
/**
 * @ngdoc overview
 * @name olikerApp
 * @description
 * # olikerApp
 *
 * Main module of the application.
 */
angular.module('olikerApp.Common.Message', [])
    .config(function($stateProvider, $urlRouterProvider) {
        var getToken = {
            'TokenServiceData': function(TokenService, $q) {
                return $q.all({
                    AuthServiceData: TokenService.promise,
                    SettingServiceData: TokenService.promiseSettings
                });
            }
        };
        $urlRouterProvider.otherwise('/');
        $stateProvider.state('messages', {
                url: '/messages/:type',
                templateUrl: 'scripts/plugins/Common/Message/views/default/messages.html',
                resolve: getToken
            })
            .state('message_view', {
                url: '/message/:id',
                templateUrl: 'scripts/plugins/Common/Message/views/default/message_view.html',
                resolve: getToken
            });
    });