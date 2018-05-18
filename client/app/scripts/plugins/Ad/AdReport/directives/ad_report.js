angular.module('olikerApp.Ad.AdReport')
    .directive('report', function($rootScope, md5, $window, $uibModal, $filter, $state, flash, AdFavoritesFactory, AdFavoriteFactory) {
        return {
            templateUrl: 'scripts/plugins/Ad/AdReport/views/default/report.html',
            restrict: 'EA',
            replace: true,
            scope: true,
            link: function postLink(scope, element, attr) {
                scope.flag = {};
                scope.openReportModal = function() {
                    scope.modalInstance = $uibModal.open({
                        templateUrl: 'scripts/plugins/Ad/AdReport/views/default/ad_report_modal.html',
                        backdrop: 'true',
                        controller: 'ReportModalInstanceController',
                        resolve: {
                            ad_reports: function() {
                                return scope.ad_reports;
                            },
                            ad_id: function() {
                                return scope.ad_id;
                            },
                            ad: function() {
                                return scope.ad;
                            }
                        }
                    });
                };
            }
        };
    });