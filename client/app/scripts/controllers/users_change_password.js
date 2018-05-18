'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersChangePasswordController
 * @description
 * # UsersChangePasswordController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UsersChangePasswordController', function($rootScope, $scope, $location, flash, UserChangePasswordFactory, $filter, $cookies) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Change Password");
        vm.head_title = $filter("translate")("Change Password");
        vm.head_description = $filter("translate")("Manage Your Password");
        vm.save_btn = false;
        vm.init = function() {
            vm.active_tab5 = "active";
            vm.password_tab = "active";
            vm.loader = true;
            vm.setLoaderDisable();
        };
        vm.setLoaderDisable = function() {
            vm.loader = false;
        };
        vm.save = function() {
            if (vm.userChangePassword.$valid && !vm.save_btn) {
                vm.save_btn = true;
                vm.changePassword.id = $rootScope.user.id;
                delete vm.changePassword.repeat_password;
                UserChangePasswordFactory.changePassword(vm.changePassword, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        if (parseInt($rootScope.settings.USER_IS_LOGOUT_AFTER_CHANGE_PASSWORD)) {
                            $cookies.remove('auth');
                            $cookies.remove('token');
                            $scope.$emit('updateParent', {
                                isAuth: false
                            });
                            flash.set($filter("translate")("Your password has been changed successfully. Please login now"), 'success', false);
                            $location.path('/users/login');
                        } else {
                            vm.changePassword = {};
                            vm.save_btn = false;
                            flash.set($filter("translate")("Your password has been changed successfully."), 'success', false);
                        }
                    } else {
                        flash.set($filter("translate")("Your old password is incorrect, please try again."), 'error', false);
                        vm.save_btn = false;
                    }
                });
            }
        };
        vm.init();
    });