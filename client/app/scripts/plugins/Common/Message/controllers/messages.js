'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:MesssageController
 * @description
 * # MesssagesController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Common.Message')
    .controller('MesssagesController', function($rootScope, $stateParams, $location, $filter, $state, $timeout, md5, ScrollPageFactory, MessagesFactory) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Messages");
        vm.head_title = $filter("translate")("Messages");
        vm.head_description = $filter("translate")("Manage All Your Messages");
        vm.message_type = $stateParams.type;
        vm.index = function() {
            vm.active_tab3 = "active";
            vm.current_page = 1;
            vm.loader = true;
            vm.maxSize = 5;
            if ($stateParams.type === 'inbox') {
                vm.inbox_tab = "active";
            } else {
                vm.sent_tab = "active";
            }
            vm.getMessages();
        };
        vm.getMessages = function() {
            var params = {};
            params.type = $stateParams.type;
            params.current_page = vm.current_page;
            MessagesFactory.get(params, function(response) {
                vm.messages = response.data;
                if (angular.isDefined(response._metadata)) {
                    vm.total_items = response._metadata.total;
                    vm.current_page = response._metadata.current_page;
                    vm.items_per_page = response._metadata.per_page;
                    vm.no_of_pages = response._metadata.last_page;
                }
                vm.loader = false;
            });
        };
        vm.paginate = function() {
            vm.current_page = parseInt(vm.current_page);
            vm.getMessages();
            ScrollPageFactory.scrollPageTop();
        };
        vm.viewMessage = function(id) {
            $state.go('message_view', {
                id: id
            });
        };
        vm.index();
    });