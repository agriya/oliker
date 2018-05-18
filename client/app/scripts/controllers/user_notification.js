'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:UsersSettingsController
 * @description
 * # UsersSettingsController
 * Controller of the oliker
 */
angular.module('olikerApp')
    .controller('UsersNotificationController', function($rootScope, userNotificationFactory, flash, $filter, UserFactory) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Notifications");
        vm.head_title = $filter("translate")("Notifications");
        vm.head_description = $filter("translate")("Manage All Your Notifications");
        vm.userNotificationUpdate = function() {
            if (vm.userNotification.$valid) {
                var flashMessage;
                vm.user.is_price_reduced_on_favorite_ads_to_email = (vm.user.is_price_reduced_on_favorite_ads_to_email) ? true : false;
                vm.user.is_price_reduced_on_favorite_ads_to_sms = (vm.user.is_price_reduced_on_favorite_ads_to_sms) ? true : false;
                vm.user.is_new_messages_received_notification_to_sms = (vm.user.is_new_messages_received_notification_to_sms) ? true : false;
                vm.user.is_new_messages_received_notification_to_email = (vm.user.is_new_messages_received_notification_to_email) ? true : false;
                vm.user.is_new_ads_on_saved_searches_to_sms = (vm.user.is_new_ads_on_saved_searches_to_sms) ? true : false;
                vm.user.is_new_ads_on_saved_searches_to_email = (vm.user.is_new_ads_on_saved_searches_to_email) ? true : false;
                userNotificationFactory.update({
                    userNotificationId: vm.user_details.user_notification.id
                }, vm.user, function(response) {
                    vm.response = response;
                    if (vm.response.error.code === 0) {
                        flashMessage = $filter("translate")("User Notifications has been Updated.");
                        flash.set(flashMessage, 'success', false);
                    } else {
                        flashMessage = $filter("translate")(response.error.message);
                        flash.set(flashMessage, 'error', false);
                    }
                });
            }
        };
        vm.index = function() {
            vm.active_tab5 = "active";
            vm.notification_tab = 'active';
            vm.loader = true;
            vm.getUser();
        };
        vm.getUser = function() {
            vm.user_deatails = {};
            var params = {};
            params.userId = $rootScope.user.id;
            UserFactory.get(params, function(response) {
                vm.user_details = response.data;
                vm.loader = false;
                vm.getNotifications();
            });
        };
        vm.getNotifications = function() {
            var params = {};
            params.userNotificationId = vm.user_details.user_notification.id;
            userNotificationFactory.get(params, function(response) {
                vm.user = {};
                vm.user.is_price_reduced_on_favorite_ads_to_email = response.data.is_price_reduced_on_favorite_ads_to_email;
                vm.user.is_price_reduced_on_favorite_ads_to_sms = response.data.is_price_reduced_on_favorite_ads_to_sms;
                vm.user.is_new_messages_received_notification_to_sms = response.data.is_new_messages_received_notification_to_sms;
                vm.user.is_new_messages_received_notification_to_email = response.data.is_new_messages_received_notification_to_email;
                vm.user.is_new_ads_on_saved_searches_to_sms = response.data.is_new_ads_on_saved_searches_to_sms;
                vm.user.is_new_ads_on_saved_searches_to_email = response.data.is_new_ads_on_saved_searches_to_email;
            });
        };
        vm.index();
    });