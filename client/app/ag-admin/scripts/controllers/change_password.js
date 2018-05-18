'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersChangePasswordController
 * @description
 * # UsersChangePasswordController
 * Controller of the olikerApp
 */
angular.module('base')
    .controller('ChangePasswordController', function($state, $scope, $http, ChangePasswordFactory, $location, notification) {
        var id = $state.params.id;
        $scope.ChangePassword = function() {
            $scope.changePassword.id = id;
            delete $scope.changePassword.confirm_password;
            ChangePasswordFactory.update($scope.changePassword, function(response) {
                if (response.error.code === 0) {
                    notification.log('Your password has been changed successfully.', {
                        addnCls: 'humane-flatty-success'
                    });
                    $location.path('/users/list');
                } else {
                    notification.log('Your old password is incorrect, please try again', {
                        addnCls: 'humane-flatty-error'
                    });
                }
            });
        }
    });