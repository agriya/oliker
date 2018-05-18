'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UserActivationController
 * @description
 * # UserActivationController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UserActivationController', function($rootScope, $scope, $location, flash, UserActivationFactory, $stateParams, $filter, $cookies) {
        var vm = this;
        var element = {};
        element.user_id = $stateParams.user_id;
        element.hash = $stateParams.hash;
        UserActivationFactory.activation(element, function(response) {
            vm.response = response;
            if (vm.response.error.code === 0) {
                delete vm.response.scope;
                if (parseInt($rootScope.settings.USER_IS_AUTO_LOGIN_AFTER_REGISTER)) {
                    $cookies.put('auth', angular.toJson(vm.response), {
                        path: '/'
                    });
                    $cookies.put('token', vm.response.access_token, {
                        path: '/'
                    });
                    $rootScope.user = vm.response;
                    $scope.$emit('updateParent', {
                        isAuth: true
                    });
                    flash.set($filter("translate")("You have successfully activated and logged in to your account."), 'success', false);
                } else if (parseInt($rootScope.settings.USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER)) {
                    flash.set($filter("translate")("You have successfully activated your account. But you can login after admin activate your account."), 'success', false);
                } else {
                    flash.set($filter("translate")("You have successfully activated your account. Now you can login."), 'success', false);
                }
                $location.path('/users/login');
            } else {
                flash.set($filter("translate")("Invalid activation request."), 'error', false);
            }
        });
    });