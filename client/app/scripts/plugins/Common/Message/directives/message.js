angular.module('olikerApp.Common.Message')
    .directive('message', function($rootScope, md5, $window, $uibModal, $filter, $state, flash) {
        return {
            templateUrl: 'scripts/plugins/Common/Message/views/default/message.html',
            restrict: 'EA',
            replace: true,
            scope: true,
            link: function postLink(scope, element, attr) {}
        };
    });