var ngapp = angular.module('base', ['ng-admin', 'http-auth-interceptor', 'angular-md5', 'ui.bootstrap', 'ngResource', 'angular.filter', 'ngCookies']);
var admin_api_url = '/';
var limit_per_page = 20;
var site_settings;
var auth;
var $cookies;
angular.injector(['ngCookies'])
    .invoke(['$cookies', function(_$cookies_) {
        $cookies = _$cookies_;
    }]);
ngapp.config(['$httpProvider',
    function($httpProvider) {
        $httpProvider.interceptors.push('interceptor');
        $httpProvider.interceptors.push('oauthTokenInjector');
        menucollaps();
    }
]);
deferredBootstrapper.bootstrap({
    element: document.body,
    module: 'base',
    resolve: {
        CmsConfig: function($http) {
            return $http.get(admin_api_url + 'api/v1/admin-config');
        }
    }
});
if ($cookies.get('auth') !== undefined && $cookies.get('auth') !== null) {
    auth = JSON.parse($cookies.get('auth'));
}
if ($cookies.get('SETTINGS') !== undefined && $cookies.get('SETTINGS') !== null) {
    site_settings = JSON.parse($cookies.get('SETTINGS'));
}
ngapp.config(function($stateProvider) {
    var getToken = {
        'TokenServiceData': function(adminTokenService, $q) {
            return $q.all({
                AuthServiceData: adminTokenService.promise,
                SettingServiceData: adminTokenService.promiseSettings
            });
        }
    };
    $stateProvider.state('login', {
            url: '/users/login',
            templateUrl: 'views/users_login.html',
            resolve: getToken
        })
        .state('plugins', {
            parent: 'main',
            url: '/plugins',
            controller: 'pluginsController',
            controllerAs: 'controller',
            templateUrl: 'views/plugins.tpl.html',
            resolve: getToken
        })
        .state('payment_gateways', {
            parent: 'main',
            url: '/payment_gateways',
            controller: 'PaymentGatewayCtrl',
            templateUrl: 'views/payment_gateway.html',
            resolve: getToken
        })
        .state('change_password', {
            parent: 'main',
            url: '/change_password',
            templateUrl: 'views/change_password.html',
            params: {
                id: null
            },
            controller: 'ChangePasswordController',
        })
        .state('logout', {
            url: '/users/logout',
            controller: 'UsersLogoutCtrl',
            resolve: getToken
        });
});
ngapp.directive('googlePlaces', ['$location', function($location) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        link: function(scope) {
            var inputFrom = document.getElementById('goo-place');
            var autocompleteFrom = new google.maps.places.Autocomplete(inputFrom);
            google.maps.event.addListener(autocompleteFrom, 'place_changed', function() {
                scope.entry()
                    .values['city.name'] = '';
                scope.entry()
                    .values['address'] = '';
                scope.entry()
                    .values['address1'] = '';
                scope.entry()
                    .values['state.name'] = '';
                scope.entry()
                    .values['country.iso_alpha2'] = '';
                scope.entry()
                    .values['zip_code'] = '';
                var place = autocompleteFrom.getPlace();
                scope.entry()
                    .values.latitude = place.geometry.location.lat();
                scope.entry()
                    .values.longitude = place.geometry.location.lng();
                var k = 0;
                angular.forEach(place.address_components, function(value, key) {
                    //jshint unused:false
                    if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                        if (k === 0) {
                            scope.entry()
                                .values['city.name'] = value.long_name;
                            // document.getElementById("city.name")
                            //     .disabled = true;
                        }
                        if (value.types[0] === 'locality') {
                            k = 1;
                        }
                    }
                    if (value.types[0] === 'premise' || value.types[0] === 'route') {
                        if (scope.entry()
                            .values['address'] !== '') {
                            scope.entry()
                                .values['address'] = scope.entry()
                                .values['address'] + ',' + value.long_name;
                        } else {
                            scope.entry()
                                .values['address'] = value.long_name;
                        }
                    }
                    if (value.types[0] === 'sublocality_level_1' || value.types[0] === 'sublocality_level_2') {
                        if (scope.entry()
                            .values['address1'] !== '') {
                            scope.entry()
                                .values['address1'] = scope.entry()
                                .values['address1'] + ',' + value.long_name;
                        } else {
                            scope.entry()
                                .values['address1'] = value.long_name;
                        }
                    }
                    if (value.types[0] === 'administrative_area_level_1') {
                        scope.entry()
                            .values['state.name'] = value.long_name;
                        document.getElementById("state.name")
                            .disabled = true;
                    }
                    if (value.types[0] === 'country') {
                        scope.entry()
                            .values['country.iso_alpha2'] = value.short_name;
                        document.getElementById("country.iso_alpha2")
                            .disabled = true;
                    }
                    if (value.types[0] === 'postal_code') {
                        scope.entry()
                            .values.zip_code = parseInt(value.long_name);
                        document.getElementById("zip_code")
                            .disabled = true;
                    }
                });
                scope.$apply();
            });
        },
        template: '<input class="form-control" id="goo-place"/>'
    };
}]);
ngapp.directive('changePassword', ['$location', '$state', '$http', 'notification', function($location, $state, $http, notification) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@"
        },
        template: '<a class=\"btn btn-default btn-xs\" title="Change Password" ng-click=\"password()\" >\n<span class=\"glyphicon glyphicon-lock sync-icon\" aria-hidden=\"true\"></span>&nbsp;<span class=\"sync hidden-xs\"> {{label}}</span> <span ng-show=\"disableButton\"><i class=\"fa fa-spinner fa-pulse fa-lg\"></i></span>\n</a>',
        link: function(scope, element) {
            var id = scope.entry()
                .values.id;
            scope.password = function() {
                $state.go('change_password', {
                    id: id
                });
            };
        }
    };
}]);
ngapp.directive('categoryFormField', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.category = scope.entry()
                .values;
        },
        templateUrl: 'views/category_form_field.html',
    };
});
ngapp.directive('adCategoryFormField', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.ad_fields = scope.entry()
                .values.ad_form_field;
            scope.ad_form_fields = [];
            angular.forEach(scope.ad_fields, function(value, key) {
                angular.forEach(value.form_field, function(field, key) {
                    scope.ad_form_fields.push(field);
                });
            });
        },
        templateUrl: 'views/ad_form_field.html',
    };
});
ngapp.directive('paymentGateway', function(paymentGateway, zazpaySynchronize) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&"
        },
        controller: function($rootScope, $scope, $location, notification) {
            angular.element(document.querySelector('ma-submit-button')
                .remove());
            $scope.test_mode_value = {};
            $scope.live_mode_value = {};
            $scope.save = function() {
                $scope.data = {};
                $scope.data.test_mode_value = $scope.test_mode_value;
                $scope.data.live_mode_value = $scope.live_mode_value;
                $scope.data.id = $scope.entry()
                    .values.id;
                paymentGateway.update($scope.data, function(response) {
                    if (angular.isDefined(response.error.code === 0)) {
                        notification.log('Data updated successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            };
            $scope.zazpay_synchronize = function() {
                zazpaySynchronize.get({}, function(response) {
                    if (angular.isDefined(response.error.code === 0)) {
                        notification.log('Synchronize with zazpay successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                    }
                });
            };
            $scope.index = function() {
                angular.forEach($scope.entry()
                    .values.payment_settings,
                    function(value, key) {
                        $scope.test_mode_value[value.name] = value.test_mode_value;
                        $scope.live_mode_value[value.name] = value.live_mode_value;
                    });
            };
            $scope.index();
        },
        template: '<input type="checkbox" ng-model="live_mode">&nbsp;<label>Live Mode</label><table><tr><th></th><th>Live Mode Credential</th><th>&nbsp;</th><th>Test Mode Credential</th></tr><tr><td>Merchant ID &nbsp;&nbsp;</td><td><input type="text" ng-model="live_mode_value.zazpay_merchant_id" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-readonly="live_mode" ng-model="test_mode_value.zazpay_merchant_id" style="margin-bottom:10px;"></td></tr><tr><td>Website ID</td><td><input type="text" class="form-control" ng-model="live_mode_value.zazpay_website_id" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" class="form-control" ng-readonly="live_mode" ng-model="test_mode_value.zazpay_website_id" style="margin-bottom:10px;"></td></tr><tr><td>Secret Key</td><td><input type="text" ng-model="live_mode_value.zazpay_secret_string" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" ng-readonly="live_mode" ng-model="test_mode_value.zazpay_secret_string" class="form-control" style="margin-bottom:10px;"></td></tr><tr><td>API Key</td><td><input type="text" ng-model="live_mode_value.zazpay_api_key" class="form-control" style="margin-bottom:10px;"></td><td>&nbsp;</td><td><input type="text" ng-readonly="live_mode" ng-model="test_mode_value.zazpay_api_key" class="form-control" style="margin-bottom:10px;"></td></tr><tr><td>&nbsp;</td><td><button type="button" ng-click="save()" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;<span class="hidden-xs">Save changes</span></button></td><td>&nbsp;</td><td><button type="button" ng-click="zazpay_synchronize()" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span>&nbsp;<span class="hidden-xs">Sync with zazpay</span></button></td></tr></table>',
    };
});
ngapp.directive('displayImage', function(md5) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            scope.type = attrs.type;
            scope.thumb = attrs.thumb;
            angular.forEach(scope.entry()
                .values['attachment'],
                function(value, key) {
                    if (angular.isDefined(scope.entry()
                            .values['attachment'][0]['foreign_id']) && scope.entry()
                        .values['attachment'][0]['foreign_id'] !== null && scope.entry()
                        .values['attachment'][0]['foreign_id'] !== 0) {
                        var hash = md5.createHash(scope.type + value.id + 'png' + scope.thumb);
                        scope.image = '/images/' + scope.thumb + '/' + scope.type + '/' + value.id + '.' + hash + '.png';
                    } else {
                        scope.image = '../images/no_image_available.png';
                    }
                })
        },
        template: '<img ng-src="{{image}}" height="42" width="42" />'
    };
});
ngapp.directive('inputType', function() {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            elem.bind('change', function() {
                scope.$apply(function() {
                    scope.entry()
                        .values.value = scope.value;
                    if (scope.entry()
                        .values.type === 'checkbox') {
                        scope.entry()
                            .values.value = scope.value ? 1 : 0;
                    }
                });
            });
        },
        controller: function($scope) {
            $scope.text = true;
            $scope.value = $scope.entry()
                .values.value;
            if ($scope.entry()
                .values.type === 'checkbox') {
                $scope.text = false;
                $scope.value = Number($scope.value);
            }
        },
        template: '<textarea ng-model="$parent.value" id="value" name="value" class="form-control" ng-if="text"></textarea><input type="checkbox" ng-model="$parent.value" id="value" name="value" ng-if="!text" ng-true-value="1" ng-false-value="0" ng-checked="$parent.value == 1"/>'
    };
});
ngapp.directive('displayType', function() {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entry: "&"
        },
        link: function(scope, elem, attrs) {
            var users = scope.entry()
                .values;
            if (scope.entry()
                .values.type === 'AdFeaturesUpdatedFee') {
                scope.description = users['user.username'] + ' ' + 'Ad Featured Purchased';
            } else if (angular.isDefined(scope.entry()
                    .values.type === 'WithdrawRequested')) {
                scope.description = users['user.username'] + ' ' + 'Withdraw the amount';
            } else if (angular.isDefined(scope.entry()
                    .values.type === 'AdPackageFee')) {
                scope.description = users['user.username'] + ' ' + 'Ad Package Purchased';
            } else if (angular.isDefined(scope.entry()
                    .values.type === 'WithdrawRequestApproved')) {
                scope.description = users['user.username'] + ' ' + 'Withdrawal Request Approved';
            } else if (angular.isDefined(scope.entry()
                    .values.type === 'WithdrawRequestRejected')) {
                scope.description = users['user.username'] + ' ' + 'Withdrawal Request Rejected';
            } else if (angular.isDefined(scope.entry()
                    .values.type === 'AmountAddedToWallet')) {
                scope.description = users['user.username'] + ' ' + 'Amount Added to own wallet';
            }
        },
        template: '<p>{{description}}</p>'
    };
});
ngapp.directive('dashboardSummary', ['$location', '$state', '$http', function($location, $state, $http) {
    return {
        restrict: 'E',
        scope: {
            entity: "&",
            entityName: "@",
            entry: "&",
            size: "@",
            label: "@",
            revenueDetails: "&"
        },
        templateUrl: 'views/dashboardSummary.html',
        link: function(scope) {
            $http.get(admin_api_url + 'api/v1/stats')
                .success(function(response) {
                    scope.adminstats = response;
                });
        }
    };
}]);
ngapp.directive('batchActive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'active' ? 'Active' : 'Active';
            scope.icon = attrs.type == 'active' ? 'glyphicon-ok' : 'glyphicon-ok';
            scope.label = attrs.type == 'active' ? 'Active' : 'Active';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 1;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('batchDeactive', ['$location', '$state', 'notification', '$q', 'Restangular', function($location, $state, notification, $q, Restangular) {
    return {
        restrict: 'E',
        scope: {
            selection: '=',
            type: '@',
            action: '@'
        },
        link: function(scope, element, attrs) {
            const status_name = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.icon = attrs.type == 'deactive' ? 'glyphicon-remove' : 'glyphicon-remove';
            scope.label = attrs.type == 'deactive' ? 'Deactive' : 'Deactive';
            scope.action = attrs.action;
            scope.updateStatus = function(action) {
                $q.all(scope.selection.map(function(e) {
                        var p = Restangular.one('/' + action + '/' + e.values.id);
                        p.is_active = 0;
                        p.put()
                            .then(function() {
                                $state.reload()
                            })
                    }))
                    .then(function() {
                        notification.log(scope.selection.length + ' status changed to  ' + status_name, {
                            addnCls: 'humane-flatty-success'
                        });
                    })
            }
        },
        template: '<span ng-click="updateStatus(action)"><span class="glyphicon {{ icon }}" aria-hidden="true"></span>&nbsp;{{ label }}</span>'
    };
}]);
ngapp.directive('customHeader', ['$location', '$state', '$http', function($location, $state, $http, $scope) {
    return {
        restrict: 'E',
        scope: {},
        templateUrl: 'views/custom_header.html'
    };
}]);
ngapp.config(['RestangularProvider', function(RestangularProvider) {
    RestangularProvider.addFullRequestInterceptor(function(element, operation, what, url, headers, params) {
        if (operation === 'getList') {
            // custom pagination params
            if (params._page) {
                params.page = params._page;
                params.limit = params._perPage;
                params.sort = params._sortField;
                params.sortby = params._sortDir;
                delete params._sortDir;
                delete params._sortField;
                delete params._page;
                delete params._perPage;
            }
            if (params._filters) {
                for (var filter in params._filters) {
                    params[filter] = params._filters[filter];
                }
                delete params._filters;
            }
        }
        if ($cookies.get("token")) {
            var sep = url.indexOf('?') === -1 ? '?' : '&';
            url = url + sep + 'token=' + $cookies.get("token");
        }
        return {
            params: params,
            url: url
        };
    });
    RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response) {
        if (operation === "getList") {
            var headers = response.headers();
            if (typeof response.data._metadata !== 'undefined' && response.data._metadata.total !== null) {
                response.totalCount = response.data._metadata.total;
            }
        }
        return data;
    });
    //To cutomize single view results, we added setResponseExtractor.
    //Our API Edit view results single array with following data format data[{}], Its not working with ng-admin format
    //so we returned data like data[0];
    RestangularProvider.setResponseExtractor(function(data, operation, what, url) {
        var extractedData;
        // .. to look for getList operations        
        extractedData = data.data;
        return extractedData;
    });
}]);

function truncate(value) {
    if (!value) {
        return '';
    }
    return value.length > 40 ? value.substr(0, 40) + '...' : value;
}
ngapp.config(['NgAdminConfigurationProvider', 'CmsConfig', function(NgAdminConfigurationProvider, CmsConfig) {
    var nga = NgAdminConfigurationProvider;
    var admin = nga.application('OLIKER Admin')
        .baseApiUrl(admin_api_url + 'api/v1/'); // main API endpoint;
    var customHeaderTemplate = '<div class="navbar-header">' + '<button type="button" class="navbar-toggle" ng-click="isCollapsed = !isCollapsed">' + '<span class="icon-bar"></span>' + '<span class="icon-bar"></span>' + '<span class="icon-bar"></span>' + '</button>' + '<a class="al-logo ng-binding ng-scope" href="#/dashboard" ng-click="appController.displayHome()"><span>OLIKER</span> Admin Panel</a>' + '<a href="" ng-click="isCollapsed = !isCollapsed" class="collapse-menu-link ion-navicon" ba-sidebar-toggle-menu=""></a>' + '</div>' + '<custom-header></custom-header>';
    admin.header(customHeaderTemplate);
    generateMenu(CmsConfig.menus);
    // customize dashboard
    // var dashboardTpl = '<div class="row list-header"><div class="col-lg-12"><div class="page-header">' + '<h4><span>Dashboard</span></h4></div></div></div>' + '<dashboard-summary></dashboard-summary>' + '<div class="row dashboard-content"><div class="col-lg-12"><div class="panel panel-default"><ma-dashboard-panel collection="dashboardController.collections.recent_ads" entries="dashboardController.entries.recent_ads" datastore="dashboardController.datastore"></ma-dashboard-panel></div></div><div class="col-lg-6"><div class="panel panel-default"><ma-dashboard-panel collection="dashboardController.collections.recent_users" entries="dashboardController.entries.recent_users" datastore="dashboardController.datastore"></ma-dashboard-panel></div></div><div class="col-lg-6"><div class="panel panel-default"><ma-dashboard-panel collection="dashboardController.collections.recent_messages" entries="dashboardController.entries.recent_messages" datastore="dashboardController.datastore"></ma-dashboard-panel></div></div></div>';
    // admin.dashboard(nga.dashboard()
    //     .addCollection(nga.collection(nga.entity('users'))
    //         .name('recent_users')
    //         .title('Recent Users')
    //         .perPage(5)
    //         .fields([
    //             nga.field('username')
    //                 .label('Username'),
    //             nga.field('email')
    //                 .label('Email address'),
    //             nga.field('role.name')
    //                 .label('Role'),
    //         ])
    //         .order(1))
    //     .addCollection(nga.collection(nga.entity('messages'))
    //         .name('recent_messages')
    //         .title('Recent Messages')
    //         .perPage(5)
    //         .fields([
    //             nga.field('id')
    //                 .label('ID'),
    //             nga.field('user.username')
    //                 .label('Sender'),
    //             nga.field('other_user.username')
    //                 .label('Receiver'),
    //             nga.field('ad.title')
    //                 .label('AD'),
    //             nga.field('message_content.message')
    //                 .map(truncate)
    //                 .label('Message')
    //         ])
    //         .order(2))
    //     .addCollection(nga.collection(nga.entity('ads'))
    //         .name('recent_ads')
    //         .title('Recent Ads')
    //         .perPage(5)
    //         .fields([
    //             nga.field('id')
    //                 .isDetailLink(true)
    //                 .detailLinkRoute('show')
    //                 .label('ID'),
    //             nga.field('title')
    //                 .label('Title'),
    //             nga.field('ad_owner.username')
    //                 .label('User'),
    //             nga.field('category.name')
    //                 .label('Category'),
    //             nga.field('advertiser_type.name')
    //                 .label('Type'),
    //             nga.field('price')
    //                 .label('Price'),
    //             nga.field('is_show_as_top_ads', 'boolean')
    //                 .label('Top?'),
    //             nga.field('is_highlighted', 'boolean')
    //                 .label('Highlighted?'),
    //             nga.field('is_urgent', 'boolean')
    //                 .label('Urgent?'),
    //             nga.field('is_show_ad_in_top', 'boolean')
    //                 .label(' In Top?'),
    //             nga.field('ad_view_count', 'number')
    //                 .label('Views'),
    //             nga.field('ad_favorite_count', 'number')
    //                 .label('Favorites')
    //         ])
    //         .order(3))
    //     .template(dashboardTpl));
    if (angular.isDefined(CmsConfig.dashboard)) {
        dashboard_template = '';
        var collections = [];
        angular.forEach(CmsConfig.dashboard, function(v, collection) {
            var fields = [];
            dashboard_template = dashboard_template + v.addCollection.template;
            if (angular.isDefined(v.addCollection)) {
                angular.forEach(v.addCollection, function(v1, k1) {
                    if (k1 == 'fields') {
                        angular.forEach(v1, function(v2, k2) {
                            var field = nga.field(v2.name, v2.type);
                            if (angular.isDefined(v2.label)) {
                                field.label(v2.label);
                            }
                            if (angular.isDefined(v2.template)) {
                                field.template(v2.template);
                            }
                            fields.push(field);
                        });
                    }
                });
            }
            collections.push(nga.collection(nga.entity(collection))
                    .name(v.addCollection.name)
                    .title(v.addCollection.title)
                    .perPage(v.addCollection.perPage)
                    .fields(fields)
                    .order(v.addCollection.order));
        });
        dashboard_page_template = '<div class="row list-header"><div class="col-lg-12"><div class="page-header">' + '<h4><span>Dashboard</span></h4></div></div></div>' + '<dashboard-summary></dashboard-summary>' + '<div class="row dashboard-content">' + dashboard_template + '</div>';
        var nga_dashboard = nga.dashboard();
        angular.forEach(collections, function(v, k) {
            nga_dashboard.addCollection(v);
        });
        nga_dashboard.template(dashboard_page_template)
        admin.dashboard(nga_dashboard);
    }
    var entities = {};
    if (angular.isDefined(CmsConfig.tables)) {
        angular.forEach(CmsConfig.tables, function(v, table) {
            var listview = {},
                editionview = {},
                creationview = {},
                showview = {},
                editViewCheck = false,
                editViewFill = "",
                showViewCheck = false,
                showViewFill = "";
            listview.fields = [];
            editionview.fields = [];
            creationview.fields = [];
            listview.filters = [];
            listview.listActions = [];
            listview.batchActions = [];
            listview.actions = [];
            showview.fields = [];
            var edit_actions = '';
            editionview.listActions = [];
            listview.infinitePagination = "",
                listview.perPage = 10;
            entities[table] = nga.entity(table);
            if (angular.isDefined(v.listview)) {
                angular.forEach(v.listview, function(v1, k1) {
                    if (k1 == 'fields') {
                        angular.forEach(v1, function(v2, k2) {
                            var field = nga.field(v2.name, v2.type);
                            if (angular.isDefined(v2.label)) {
                                field.label(v2.label);
                            }
                            if (angular.isDefined(v2.isDetailLink)) {
                                field.isDetailLink(v2.isDetailLink);
                            }
                            if (angular.isDefined(v2.detailLinkRoute)) {
                                field.detailLinkRoute(v2.detailLinkRoute);
                            }
                            if (angular.isDefined(v2.template)) {
                                field.template(v2.template);
                            }
                            if (angular.isDefined(v2.permanentFilters)) {
                                field.permanentFilters(v2.permanentFilters);
                            }
                            if (angular.isDefined(v2.infinitePagination)) {
                                field.infinitePagination(v2.infinitePagination);
                            }
                            if (angular.isDefined(v2.singleApiCall)) {
                                if (angular.isDefined(v2.targetEntity)) {
                                    field.targetEntity(nga.entity(v2.targetEntity));
                                }
                                if (angular.isDefined(v2.targetField)) {
                                    field.targetField(nga.field(v2.targetField));
                                }
                            }
                            if (angular.isDefined(v2.singleApiCall)) {
                                field.singleApiCall(v2.singleApiCall);
                            }
                            if (angular.isDefined(v2.batchActions)) {
                                field.batchActions(v2.batchActions);
                            }
                            if (angular.isDefined(v2.stripTags)) {
                                field.stripTags(v2.stripTags);
                            }
                            if (angular.isDefined(v2.exportOptions)) {
                                field.exportOptions(v2.exportOptions);
                            }
                            if (angular.isDefined(v2.map)) {
                                angular.forEach(v2.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
                            if (angular.isDefined(v2.remoteComplete)) {
                                field.remoteComplete(true, {
                                    searchQuery: function(search) {
                                        return {
                                            q: search,
                                            autocomplete: true
                                        };
                                    }
                                });
                            }
                            listview.fields.push(field);
                        });
                    }
                    if (k1 == 'filters') {
                        angular.forEach(v1, function(v3, k3) {
                            var field;
                            if (v3.type === "template") {
                                field = nga.field(v3.name);
                            } else {
                                field = nga.field(v3.name, v3.type);
                            }
                            if (angular.isDefined(v3.label)) {
                                field.label(v3.label);
                            }
                            if (angular.isDefined(v3.choices)) {
                                field.choices(v3.choices);
                            }
                            if (angular.isDefined(v3.pinned)) {
                                field.pinned(v3.pinned);
                            }
                            if (angular.isDefined(v3.template) && v3.template !== "") {
                                field.template(v3.template);
                            }
                            if (angular.isDefined(v3.targetEntity)) {
                                field.targetEntity(nga.entity(v3.targetEntity));
                            }
                            if (angular.isDefined(v3.targetField)) {
                                field.targetField(nga.field(v3.targetField));
                            }
                            if (angular.isDefined(v3.remoteComplete)) {
                                field.remoteComplete(true, {
                                    searchQuery: function(search) {
                                        return {
                                            q: search,
                                            autocomplete: true
                                        };
                                    }
                                });
                            }
                            if (angular.isDefined(v3.map)) {
                                angular.forEach(v3.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
                            listview.filters.push(field);
                        });
                    }
                    if (k1 == 'listActions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                if (v3 === "edit") {
                                    editViewCheck = true;
                                }
                                if (v3 === "show") {
                                    showViewCheck = true;
                                }
                                listview.listActions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.listActions.push(v1);
                        }
                    }
                    if (k1 == 'batchActions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                listview.batchActions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.batchActions.push(v1);
                        }
                    }
                    if (k1 == 'actions') {
                        if (Array.isArray(v1) === true) {
                            angular.forEach(v1, function(v3, k3) {
                                listview.actions.push(v3);
                            });
                        } else if (v1 !== "") {
                            listview.actions.push(v1);
                        }
                    }
                    if (k1 == 'infinitePagination') {
                        listview.infinitePagination = v1;
                    }
                    if (k1 == 'perPage') {
                        listview.perPage = v1;
                    }
                    if (k1 == 'sortDir') {
                        listview.sortDir = v1;
                    }
                    if (k1 == 'sortField') {
                        listview.sortField = v1;
                    }
                });
                if (angular.isDefined(v.creationview)) {
                    editViewFill = generateFields(v.creationview.fields);
                    creationview.fields.push(editViewFill);
                    if (editViewCheck === true && !angular.isDefined(v.editionview)) {
                        editionview.fields.push(editViewFill);
                    } else if (angular.isDefined(v.editionview)) {
                        if (angular.isDefined(v.editionview.actions)) {
                            edit_actions = v.editionview.actions;
                        }
                        editionview.fields.push(generateFields(v.editionview.fields));
                    }
                }
            }
            if (angular.isDefined(v.showview)) {
                showview.fields.push(generateFields(v.showview.fields));
            } else if (showViewCheck === true) {
                showview.fields.push(listview.fields);
            }
            var listTitle;
            if (angular.isDefined(v.listview) && angular.isDefined(v.listview.title)) {
                listTitle = v.listview.title;
            } else {
                listTitle = table;
            }
            admin.addEntity(entities[table]);
            entities[table].listView()
                .title(listTitle)
                .fields(listview.fields)
                .listActions(listview.listActions)
                .batchActions(listview.batchActions)
                .infinitePagination(listview.infinitePagination)
                .perPage(parseInt(listview.perPage))
                .sortDir(listview.sortDir)
                .sortField(listview.sortField)
                .actions(listview.actions)
                .filters(listview.filters);
            var createTitle;
            if (angular.isDefined(v.creationview) && angular.isDefined(v.creationview.title)) {
                createTitle = v.creationview.title;
            } else {
                createTitle = table;
            }
            if (angular.isDefined(v.creationview)) {
                entities[table].creationView()
                    .title(createTitle + ' Add')
                    .fields(creationview.fields)
                    .onSubmitSuccess(['progression', 'notification', '$state', 'entry', 'entity', function(progression, notification, $state, entry, entity) {
                        progression.done();
                        notification.log(toUpperCase(entity.name()) + ' added successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        $state.go($state.get('list'), {
                            entity: entity.name()
                        });
                        return false;
                    }]);
            }
            if (angular.isDefined(v.editionview) || editViewCheck === true) {
                var editTitle;
                if (editViewCheck === true) {
                    editTitle = createTitle + ' Edit';
                } else {
                    editTitle = v.editionview.title + ' Edit';
                }
                entities[table].editionView()
                    .title(editTitle)
                    .fields(editionview.fields)
                    .actions(edit_actions)
                    .onSubmitSuccess(['progression', 'notification', '$location', '$state', 'entry', 'entity', function(progression, notification, $location, $state, entry, entity) {
                        progression.done();
                        notification.log(toUpperCase(entity.name()) + ' updated successfully', {
                            addnCls: 'humane-flatty-success'
                        });
                        if (entity.name() === 'settings') {
                            var current_id = entry.values.setting_category_id;
                            $location.path('/setting_categories/show/' + current_id);
                        } else {
                            $state.go($state.get('list'), {
                                entity: entity.name()
                            });
                        }
                        return false;
                    }])
                    .onSubmitError(['error', 'form', 'progression', 'notification', function(error, form, progression, notification) {
                        angular.forEach(error.data.errors, function(value, key) {
                            if (this[key]) {
                                this[key].$valid = false;
                            }
                        }, form);
                        progression.done();
                        notification.log(error.data.message, {
                            addnCls: 'humane-flatty-error'
                        });
                        return false;
                    }]);
            }
            if (angular.isDefined(v.showview) || showViewCheck === true) {
                if (showViewCheck === true) {
                    entities[table].showView()
                        .title(v.listview.title);
                } else if (angular.isDefined(v.showview) && angular.isDefined(v.showview.title)) {
                    entities[table].showView()
                        .title(v.showview.title);
                }
                entities[table].showView()
                    .fields(showview.fields);
            }
        });
    }

    function generateMenu(menus) {
        angular.forEach(menus, function(menu_value, menu_keys) {
            var menus;
            if (angular.isDefined(menu_value.link)) {
                menusIndex = nga.menu();
                if (angular.isDefined(menu_value.linkFunction)) {
                    menusIndex.link(menu_value.link + eval(menu_value.linkFunction));
                } else {
                    menusIndex.link(menu_value.link);
                }
            } else if (angular.isDefined(menu_value.child_sub_menu)) {
                menusIndex = nga.menu();
            } else {
                menusIndex = nga.menu(nga.entity(menu_keys));
            }
            if (angular.isDefined(menu_value.title)) {
                menusIndex.title(menu_value.title);
            }
            if (angular.isDefined(menu_value.icon_template)) {
                menusIndex.icon(menu_value.icon_template);
            }
            if (angular.isDefined(menu_value.child_sub_menu)) {
                angular.forEach(menu_value.child_sub_menu, function(val, key) {
                    var child = nga.menu(nga.entity(key));
                    if (angular.isDefined(val.title)) {
                        child.title(val.title);
                    }
                    if (angular.isDefined(val.icon_template)) {
                        child.icon(val.icon_template);
                    }
                    if (angular.isDefined(val.link)) {
                        if (angular.isDefined(val.linkFunction)) {
                            child.link(val.link + eval(val.linkFunction));
                        } else {
                            child.link(val.link);
                        }
                    }
                    menusIndex.addChild(child);
                });
            }
            admin.menu()
                .addChild(menusIndex);
        });
    }

    function generateFields(fields) {
        var generatedFields = [];
        angular.forEach(fields, function(targetFieldValue, targetFieldKey) {
            var field = nga.field(targetFieldValue.name, targetFieldValue.type),
                fieldAdd = true;
            if (angular.isDefined(targetFieldValue.label)) {
                field.label(targetFieldValue.label);
            }
            if (angular.isDefined(targetFieldValue.choices)) {
                field.choices(targetFieldValue.choices);
            }
            if (angular.isDefined(targetFieldValue.editable)) {
                field.editable(targetFieldValue.editable);
            }
            if (angular.isDefined(targetFieldValue.attributes)) {
                field.attributes(targetFieldValue.attributes);
            }
            if (angular.isDefined(targetFieldValue.targetEntity)) {
                field.targetEntity(nga.entity(targetFieldValue.targetEntity));
            }
            if (angular.isDefined(targetFieldValue.targetField)) {
                field.targetField(nga.field(targetFieldValue.targetField));
            }
            if (angular.isDefined(targetFieldValue.targetReferenceField)) {
                field.targetReferenceField(targetFieldValue.targetReferenceField);
            }
            if (angular.isDefined(targetFieldValue.format)) {
                field.format(targetFieldValue.format);
            }
            if (angular.isDefined(targetFieldValue.template)) {
                field.template(targetFieldValue.template);
            }
            if (angular.isDefined(targetFieldValue.permanentFilters)) {
                field.permanentFilters(targetFieldValue.permanentFilters);
            }
            if (angular.isDefined(targetFieldValue.defaultValue)) {
                field.defaultValue(targetFieldValue.defaultValue);
            }
            if (angular.isDefined(targetFieldValue.validation)) {
                field.validation(eval(targetFieldValue.validation));
            }
            if (angular.isDefined(targetFieldValue.listActions)) {
                field.listActions(targetFieldValue.listActions);
            }
                if (angular.isDefined(targetFieldValue.map)) {
                                angular.forEach(targetFieldValue.map, function(v2m, k2m) {
                                    field.map(eval(v2m));
                                });
                            }
            if (angular.isDefined(targetFieldValue.remoteComplete)) {
                field.remoteComplete(true, {
                    searchQuery: function(search) {
                        return {
                            q: search,
                            autocomplete: true
                        };
                    }
                });
            }
            if (angular.isDefined(targetFieldValue.uploadInformation) && angular.isDefined(targetFieldValue.uploadInformation.url) && angular.isDefined(targetFieldValue.uploadInformation.apifilename)) {
                field.uploadInformation({
                    'url': admin_api_url + targetFieldValue.uploadInformation.url,
                    'apifilename': targetFieldValue.uploadInformation.apifilename
                });
            }
            if (targetFieldValue.type === "file" && (!angular.isDefined(targetFieldValue.uploadInformation) || !angular.isDefined(targetFieldValue.uploadInformation.url) || !angular.isDefined(targetFieldValue.uploadInformation.apifilename))) {
                fieldAdd = false;
            }
            if (angular.isDefined(targetFieldValue.targetFields) && (targetFieldValue.type === "embedded_list" || targetFieldValue.type === "referenced_list")) {
                var embField = generateFields(targetFieldValue.targetFields);
                field.targetFields(embField);
            }
            if (fieldAdd === true) {
                generatedFields.push(field);
            }
        });
        return generatedFields;
    }
    nga.configure(admin);
}]);
ngapp.run(['$rootScope', '$location', '$window', '$state', '$cookies', function($rootScope, $location, $window, $state, $cookies) {
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
        var url = toState.name;
        var exception_arr = ['login', 'logout'];
        if (($cookies.get("auth") === null || $cookies.get("auth") === undefined) && exception_arr.indexOf(url) === -1) {
            $location.path('/users/login');
        }
        if (exception_arr.indexOf(url) === 0 && $cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
            $location.path('/dashboard');
        }
        if ($cookies.get("auth") !== null && $cookies.get("auth") !== undefined) {
            var auth = JSON.parse($cookies.get("auth"));
            if (auth.role_id === 2) {
                $location.path('/users/logout');
            }
        }
        trayOpen();
    });
}]);

function addFields(getFields) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0)
            .toUpperCase() + txt.substr(1)
            .toLowerCase();
    });
}

function trayOpen() {
    setTimeout(function() {
        /* For open sub-menu tray */
        if ($('.active')
            .parents('.with-sub-menu')
            .attr('class')) {
            $('.active')
                .parents('.with-sub-menu')
                .addClass('ba-sidebar-item-expanded');
        }
        /* For open collaps menu when menu in collaps state */
        $('.al-sidebar-list-link')
            .click(function() {
                if ($('.js-collaps-main')
                    .hasClass('menu-collapsed')) {
                    $('.js-collaps-main')
                        .removeClass('menu-collapsed');
                }
            });
    }, 100);
}

function menucollaps() {
    setTimeout(function() {
        /* For menu collaps and open */
        $('.collapse-menu-link')
            .click(function() {
                if ($('.js-collaps-main')
                    .hasClass('menu-collapsed')) {
                    $('.js-collaps-main')
                        .removeClass('menu-collapsed');
                } else {
                    $('.js-collaps-main')
                        .addClass('menu-collapsed');
                }
            });
    }, 1000);
}

function toUpperCase(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0)
            .toUpperCase() + txt.substr(1)
            .toLowerCase();
    });
}