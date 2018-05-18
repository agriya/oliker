'use strict';
/**
 * @ngdoc function
 * @name olikerApp.controller:UsersSettingsController
 * @description
 * # UsersSettingsController
 * Controller of the olikerApp
 */
angular.module('olikerApp')
    .controller('UsersSettingsController', function($rootScope, $scope, UserFactory, flash, $filter, md5, Upload) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Users Settings");
        vm.head_title = $filter("translate")("Account Settings");
        vm.head_description = $filter("translate")("Manage Your Account");
        vm.save_btn = false;
        vm.getRandomizer = function(bottom, top) {
            return Math.floor(Math.random() * (1 + top - bottom)) + bottom;
        };
        vm.save = function($valid) {
            if ($valid) {
                if (!angular.isString(vm.place) && vm.place !== null) {
                    if (angular.isDefined(vm.place)) {
                        vm.userSettings.latitude = vm.place.geometry.location.lat();
                        vm.userSettings.longitude = vm.place.geometry.location.lng();
                        vm.userSettings.address = vm.place.formatted_address;
                        angular.forEach(vm.place.address_components, function(value) {
                            if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                                vm.userSettings.city.name = value.long_name;
                            }
                            if (value.types[0] === 'administrative_area_level_1') {
                                vm.userSettings.state.name = value.long_name;
                            }
                            if (value.types[0] === 'country') {
                                vm.userSettings.country.iso_alpha2 = value.short_name;
                            }
                        });
                    }
                }
                var flashMessage;
                vm.save_btn = true;
                UserFactory.update({
                    userId: $rootScope.user.id
                }, vm.userSettings, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        flashMessage = $filter("translate")("User Profile has been updated.");
                        flash.set(flashMessage, 'success', false);
                    } else {
                        flashMessage = $filter("translate")("User Profile could not be updated.");
                        flash.set(flashMessage, 'error', false);
                    }
                    vm.save_btn = false;
                });
            }
        };
        vm.upload = function(file) {
            Upload.upload({
                    url: '/api/v1/attachments',
                    data: {
                        class: 'UserAvatar',
                        file: file
                    }
                })
                .then(function(response) {
                    vm.photo = {};
                    vm.photo.image = {};
                    vm.photo.image.attachment = response.data.id;
                    vm.UserAvatarUpload();
                });
        };
        vm.UserAvatarUpload = function() {
            UserFactory.update({
                userId: $rootScope.user.id
            }, vm.photo, function(response) {
                if (response.error.code === 0) {
                    vm.user.user_avatar_url = '/images/big_normal_thumb/UserAvatar/' + response.data.attachment.id + '.' + md5.createHash('UserAvatar' + response.data.attachment.id + 'png' + 'big_normal_thumb') + '.png' + '?dyn=' + vm.getRandomizer(100, 9999);
                    $rootScope.user.attachment = response.data.attachment;
                    $rootScope.$emit('updateParent', {
                        isAuth: true,
                        auth: response.data
                    });
                }
            });
        };
        vm.photoUpload = function() {
            vm.upload();
        };
        vm.index = function() {
            vm.user = {};
            vm.active_tab5 = "active";
            vm.setting_tab = "active";
            vm.loader = true;
            var params = {};
            params.userId = $rootScope.user.id;
            UserFactory.get(params, function(response) {
                vm.userSettings = {};
                vm.userSettings.country = {};
                vm.userSettings.city = {};
                vm.userSettings.state = {};
                vm.userSettings.id = response.data.id;
                vm.userSettings.first_name = response.data.first_name;
                vm.userSettings.last_name = response.data.last_name;
                vm.userSettings.about_me = response.data.about_me;
                if (angular.isDefined(response.data.attachment) && response.data.attachment !== null) {
                    var hash = md5.createHash('UserAvatar' + response.data.attachment.id + 'png' + 'big_normal_thumb');
                    vm.user.user_avatar_url = '/images/big_normal_thumb/UserAvatar/' + response.data.attachment.id + '.' + hash + '.png';
                } else {
                    vm.user.user_avatar_url = '/images/user_no_img.png';
                }
                if (angular.isDefined(response.data.city) && response.data.city !== null) {
                    vm.userSettings.city.name = response.data.city.name;
                } else {
                    vm.userSettings.city.name = "";
                }
                if (angular.isDefined(response.data.latitude) && response.data.latitude !== null) {
                    vm.userSettings.latitude = response.data.latitude;
                } else {
                    vm.userSettings.latitude = "";
                }
                if (angular.isDefined(response.data.longitude) && response.data.longitude !== null) {
                    vm.userSettings.longitude = response.data.longitude;
                } else {
                    vm.userSettings.longitude = "";
                }
                if (angular.isDefined(response.data.address) && response.data.address !== null) {
                    vm.userSettings.address = response.data.address;
                    vm.place = vm.userSettings.address;
                } else {
                    vm.userSettings.address = "";
                }
                if (angular.isDefined(response.data.state) && response.data.state !== null) {
                    vm.userSettings.state.name = response.data.state.name;
                } else {
                    vm.userSettings.state.name = "";
                }
                if (angular.isDefined(response.data.country) && response.data.country !== null) {
                    vm.userSettings.country.iso_alpha2 = response.data.country.iso_alpha2;
                } else {
                    vm.userSettings.country.iso_alpha2 = "";
                }
                vm.loader = false;
            });
        };
        vm.index();
    });