'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:MesssageViewController
 * @description
 * # MesssageViewController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Common.Message')
    .controller('MesssageViewController', function($rootScope, $stateParams, $location, $filter, $state, $timeout, md5, MessagesFactory, MessageViewFactory) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Messages");
        vm.head_title = $filter("translate")("Messages");
        vm.loader = true;
        vm.head_description = $filter("translate")("Manage All Your Messages");
        vm.current_page = 1;
        vm.message_type = $stateParams.type;
        vm.active_tab3 = "active";
        if ($stateParams.type === 'inbox') {
            vm.inbox_tab = "active";
        } else {
            vm.sent_tab = "active";
        }
        vm.index = function() {
            vm.getMessage();
        };
        vm.getMessage = function() {
            var params = {};
            params.messageId = $stateParams.id;
            MessageViewFactory.get(params, function(response) {
                vm.message = response.data;
                angular.forEach(vm.message.attachment, function(msg) {
                    if (angular.isDefined(msg)) {
                        var hash = md5.createHash('Message' + msg.id + 'png' + 'normal_thumb');
                        msg.image_name = '/images/normal_thumb/Message/' + msg.id + '.' + hash + '.png';
                    } else {
                        msg.image_name = "/images/user_no_img.png";
                    }
                });
                vm.loader = false;
            });
        };
        vm.index();
    });