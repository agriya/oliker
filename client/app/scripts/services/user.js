'use strict';
/**
 * @ngdoc service
 * @name olikerApp.User
 * @description
 * # User
 * Factory in the olikerApp.
 */
angular.module('olikerApp')
    .factory('UserLoginFactory', function($resource) {
        return $resource('/api/v1/users/login', {}, {
            login: {
                method: 'POST'
            }
        });
    })
    .factory('TwitterLoginFactory', function($resource) {
        return $resource('/api/v1/users/social_login?type=twitter', {}, {
            login: {
                method: 'POST'
            }
        });
    })
    .factory('UserLogoutFactory', function($resource) {
        return $resource('/api/v1/users/logout', {}, {
            logout: {
                method: 'GET'
            }
        });
    })
    .factory('UserChangePasswordFactory', function($resource) {
        return $resource('/api/v1/users/:id/change_password', {}, {
            changePassword: {
                method: 'PUT',
                params: {
                    id: '@id'
                }
            }
        });
    })
    .factory('UserForgotPasswordFactory', function($resource) {
        return $resource('/api/v1/users/forgot_password', {}, {
            forgetPassword: {
                method: 'POST'
            }
        });
    })
    .factory('UserActivationFactory', function($resource) {
        return $resource('/api/v1/users/:user_id/activation/:hash', {}, {
            activation: {
                method: 'PUT',
                params: {
                    user_id: '@user_id',
                    hash: '@hash'
                }
            }
        });
    })
    .factory('UserRegisterFactory', function($resource) {
        return $resource('/api/v1/users/register', {}, {
            create: {
                method: 'POST'
            }
        });
    })
    .factory('UserFactory', function($resource) {
        return $resource('/api/v1/users/:userId', {}, {
            update: {
                method: 'PUT',
                params: {
                    userId: '@userId'
                }
            },
            get: {
                method: 'GET',
                params: {
                    userId: '@userId'
                }
            }
        });
    });