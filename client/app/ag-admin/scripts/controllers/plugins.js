angular.module('base')
    .controller('pluginsController', function($scope, $http, notification, $cookies, $state, $window) {
        $scope.languageArr = [];

        function getPluginDetails() {
            $http.get(admin_api_url + 'api/v1/plugins', {})
                .success(function(response) {
                    $scope.ads_plugin = response.data.ad_plugin;
                    $scope.payment_and_cart_plugin = response.payment_and_cart_plugin;
                    $scope.payment_gateway_plugin = response.payment_gateway_plugin;
                    $scope.other_plugin = response.data.other_plugin;
                    $scope.enabled_plugin = response.data.enabled_plugin;
                    enabledPlugin = response.enabled_plugin;
                    $cookies.put('enabled_plugins', JSON.stringify(enabledPlugin), {
                        path: '/'
                    });
                }, function(error) {});
        }
        $scope.checkStatus = function(plugin, enabled_plugins) {
            if ($.inArray(plugin, enabled_plugins) > -1) {
                return true;
            } else {
                return false;
            }
        };
        $scope.updatePluginStatus = function(e, plugin_name, status, hash) {
            e.preventDefault();
            var target = angular.element(e.target);
            checkDisabled = target.parent()
                .hasClass('disabled');
            if (checkDisabled === true) {
                return false;
            }
            var params = {};
            var confirm_msg = '';
            params.plugin = plugin_name;
            params.is_enabled = status;
            confirm_msg = (status === 0) ? "Are you sure want to disable?" : "Are you sure want to enable?";
            notification_msg = (status === 0) ? "disabled" : "enabled";
            if (confirm(confirm_msg)) {
                $http.put(admin_api_url + 'api/v1/plugins', params)
                    .success(function(response) {
                        if (response.error.code === 0) {
                            notification.log(plugin_name + ' Plugin ' + notification_msg + ' successfully.', {
                                addnCls: 'humane-flatty-success'
                            });
                            getPluginDetails();
                        }
                    }, function(error) {});
            }
        };
        $scope.fullRefresh = function() {
            $window.location.reload();
        };
        getPluginDetails();
    });