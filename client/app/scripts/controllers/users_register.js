'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersRegisterController
 * @description
 * # UsersRegisterController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UsersRegisterController', function($rootScope, $scope, UserRegisterFactory, flash, $location, $timeout, vcRecaptchaService, $filter, $cookies, $uibModalStack, $document) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Register");
        /*jshint -W117 */
        function validatePassword() {
            var pass2 = $document[0].getElementById("password")
                .value;
            var pass1 = $document[0].getElementById("confirm-password")
                .value;
            if (pass2 !== null && pass1 !== null && pass1 !== pass2) {
                $document[0].getElementById("confirm-password")
                    .setCustomValidity("Password Mismatch");
            } else {
                $document[0].getElementById("confirm-password")
                    .setCustomValidity("");
            }
        }
        angular.element($document[0])
            .on('blur change', "#password, #confirm-password", function() {
                validatePassword();
            });
        angular.element($document[0])
            .ready(function() {
                if ($document[0].getElementById("is_agree_terms_conditions")
                    .checked === false) {
                    $document[0].getElementById("is_agree_terms_conditions")
                        .setCustomValidity("You must agree to the terms and conditions");
                } else {
                    $document[0].getElementById("is_agree_terms_conditions")
                        .setCustomValidity("");
                }
            });
        vm.save = function() {
            var response = vcRecaptchaService.getResponse(vm.widgetId);
            if (response.length === 0) {
                vm.captchaErr = $filter("translate")("Please resolve the captcha and submit");
            } else {
                vm.captchaErr = '';
            }
            if (vm.userSignup.$valid) {
                UserRegisterFactory.create(vm.user, function(response) {
                    vm.response = response;
                    delete vm.response.scope;
                    if (vm.response.error.code === 0) {
                        vm.redirect = false;
                        if (parseInt($rootScope.settings.USER_IS_AUTO_LOGIN_AFTER_REGISTER)) {
                            vm.redirect = true;
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
                            flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                        } else if (parseInt($rootScope.settings.USER_IS_EMAIL_VERIFICATION_FOR_REGISTER) && parseInt($rootScope.settings.USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER)) {
                            flash.set($filter("translate")("You have successfully registered with our site you can login after email verification and administrator approval. Your activation mail has been sent to your mail inbox."), 'success', false);
                        } else if (parseInt($rootScope.settings.USER_IS_ADMIN_ACTIVATE_AFTER_REGISTER)) {
                            flash.set($filter("translate")("You have successfully registered with our site. After administrator approval you can login to site."), 'success', false);
                        } else if (parseInt($rootScope.settings.USER_IS_EMAIL_VERIFICATION_FOR_REGISTER)) {
                            flash.set($filter("translate")("You have successfully registered with our site and your activation mail has been sent to your mail inbox."), 'success', false);
                        } else {
                            flash.set($filter("translate")("You have successfully registered with our site."), 'success', false);
                        }
                        if ($cookies.get("redirect_url") !== null && angular.isDefined($cookies.get("redirect_url")) && vm.redirect) {
                            $location.path($cookies.get("redirect_url"));
                            $cookies.remove('redirect_url');
                        } else {
                            $timeout(function() {
                                $location.path('/');
                            }, 1000);
                        }
                        $uibModalStack.dismissAll();
                    } else {
                        if (angular.isDefined(vm.response.error.fields) && angular.isDefined(vm.response.error.fields.unique) && vm.response.error.fields.unique.length !== 0) {
                            flash.set($filter("translate")("Please choose different " + vm.response.error.fields.unique.join()), 'error', false);
                        } else {
                            flash.set($filter("translate")("User could not be added. Please, try again"), 'error', false);
                        }
                        vcRecaptchaService.reload(vm.widgetId);
                    }
                }, function(error) {
                    if (angular.isDefined(error.data.error.fields) && angular.isDefined(error.data.error.fields.unique) && error.data.error.fields.unique.length !== 0) {
                        flash.set($filter("translate")("Please choose different " + error.data.error.fields.unique.join()), 'error', false);
                    } else {
                        flash.set($filter("translate")("User could not be added. Please, try again"), 'error', false);
                    }
                    vcRecaptchaService.reload(vm.widgetId);
                });
            }
        };
    });