'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersForgotPasswordController
 * @description
 * # UsersForgotPasswordController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UsersForgotPasswordController', function($rootScope, $state, flash, UserForgotPasswordFactory, $filter, vcRecaptchaService, $uibModalStack) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Forgot Password");
        vm.save_btn = false;
        $uibModalStack.dismissAll();
        if (parseInt($rootScope.settings.USER_IS_CAPTCHA_ENABLED_FORGOT_PASSWORD)) {
            vm.show_recaptcha = true;
        }
        vm.save = function() {
            if (vm.userForgotPassword.$valid && !vm.save_btn) {
                vm.save_btn = true;
                UserForgotPasswordFactory.forgetPassword(vm.user, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        flash.set($filter("translate")("We have sent an email to " + vm.user.email + " with further instructions."), 'success', false);
                        $state.go('users_login');
                    } else {
                        vm.save_btn = false;
                        flash.set($filter("translate")("There is no user registered with the email " + vm.user.email + " or admin deactivated your account. If you spelled the address incorrectly or entered the wrong address, please try again."), 'error', false);
                        vm.user = {};
                        if (parseInt($rootScope.settings.USER_IS_CAPTCHA_ENABLED_FORGOT_PASSWORD)) {
                            vcRecaptchaService.reload(vm.widgetId);
                        }
                    }
                });
            }
        };
    });