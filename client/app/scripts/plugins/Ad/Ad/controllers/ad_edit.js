'use strict';
/**
 * @ngdoc function
 * @name oliker.controller:MyAdsListController
 * @description
 * # MyAdsListController
 * Controller of the olikerApp
 */
angular.module('olikerApp.Ad')
    .controller('AdEditController', function($rootScope, $filter, $state, $stateParams, AdFactory, AttachmentDeleteFactory, AdsAdvertiserTypeFactory, $builder, CategoriesFactory, Upload, flash, $uibModal, md5) {
        var vm = this;
        $rootScope.header = $rootScope.settings.SITE_NAME + ' | ' + $filter("translate")("Edit Ad");
        vm.ad = {};
        vm.input = [];
        vm.ad.image = [];
        vm.ad_images = [];
        vm.ad_image_files = [];
        if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
            vm.form = [];
            var c = $builder.forms['default'].length;
            for (var i = 0; i < c; i++) {
                $builder.removeFormObject('default', i);
            }
            $builder.removeFormObject('default');
        }
        vm.index = function() {
            vm.getAdvertiserType();
            vm.getCategories();
            AdFactory.get({
                adId: $stateParams.id
            }, function(response) {
                vm.ad_details = response.data;
                vm.ad.title = response.data.title;
                vm.ad.description = response.data.description;
                vm.ad.price = response.data.price;
                vm.ad.advertiser_name = response.data.advertiser_name;
                vm.ad.is_negotiable = response.data.is_negotiable;
                vm.ad.is_an_exchange_item = response.data.is_an_exchange_item;
                vm.ad.is_send_email_when_user_contact = response.data.is_send_email_when_user_contact;
                vm.ad.advertiser_type_id = response.data.advertiser_type_id;
                vm.category_id = response.data.category_id;
                vm.ad.category_id = response.data.category_id;
                vm.category_chosen = response.data.category;
                vm.ad.phone_number = response.data.phone_number;
                // vm.category =filterFilter(vm.categories,{id:response.data.category_id});vm.selectcategory(vm.category);
                if (angular.isDefined(response.data.city) && response.data.city !== null) {
                    vm.ad.city_name = response.data.city.name;
                } else {
                    vm.ad.city_name = "";
                }
                if (angular.isDefined(response.data.latitude) && response.data.latitude !== null) {
                    vm.ad.latitude = response.data.latitude;
                } else {
                    vm.ad.latitude = "";
                }
                if (angular.isDefined(response.data.longitude) && response.data.longitude !== null) {
                    vm.ad.longitude = response.data.longitude;
                } else {
                    vm.ad.longitude = "";
                }
                if (angular.isDefined(response.data.location) && response.data.location !== null) {
                    vm.ad.location = response.data.location;
                    vm.place = vm.ad.location;
                } else {
                    vm.userSettings.address = "";
                }
                if (angular.isDefined(response.data.state) && response.data.state !== null) {
                    vm.ad.state_name = response.data.state.name;
                } else {
                    vm.ad.state_name = "";
                }
                if (angular.isDefined(response.data.country) && response.data.country !== null) {
                    vm.ad.country_iso2 = response.data.country.iso_alpha2;
                } else {
                    vm.ad.country_iso2 = "";
                }
                if ((angular.isDefined(response.data.attachment) && response.data.attachment.length > 0)) {
                    angular.forEach(response.data.attachment, function(value, key) {
                        vm.image_data = {};
                        var hash = md5.createHash('Ad' + value.id + 'png' + 'small_normal_thumb');
                        vm.image_data.name = '/images/small_normal_thumb/Ad/' + value.id + '.' + hash + '.png';
                        vm.image_data.id = value.id;
                        vm.ad_images.push(vm.image_data);
                    });
                }
                if (angular.isDefined(response.data.ad_form_field)) {
                    vm.defaultValue = {};
                    if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
                        vm.form = [];
                        var c = $builder.forms['default'].length;
                        for (var i = 0; i < c; i++) {
                            $builder.removeFormObject('default', i);
                        }
                        $builder.removeFormObject('default');
                    }
                    vm.form_field_name = [];
                    angular.forEach(response.data.ad_form_field, function(ad_fields) {
                        angular.forEach(ad_fields.form_field, function(fields) {
                            var temp = $builder.addFormObject('default', {
                                id: fields.id,
                                component: fields.input_types.name,
                                label: fields.label,
                                description: fields.info,
                                placeholder: '',
                                required: fields.is_required,
                                editable: false
                            });
                            vm.form_field_name[temp.id] = fields.name;
                        });
                    });
                    vm.form = $builder.forms['default'];
                    angular.forEach(vm.ad_details.ad_form_field, function(data) {
                        vm.defaultValue[data.form_field_id] = data.response;
                    });
                }
            });
        };
        vm.selectcategory = function(category) {
            if (angular.isDefined(category) && category !== null) {
                vm.ad.category_id = category.id;
            }
            vm.category = category;
            vm.form_fields = category.form_field;
            if (angular.isDefined($builder.forms['default']) && $builder.forms['default'].length > 0) {
                vm.form = [];
                var c = $builder.forms['default'].length;
                for (var i = 0; i < c; i++) {
                    $builder.removeFormObject('default', i);
                }
                if ($builder.forms['default'].length > 0) {
                    vm.form = [];
                    var d = $builder.forms['default'].length;
                    for (var j = 0; j < d; j++) {
                        $builder.removeFormObject('default', j);
                    }
                }
                $builder.removeFormObject('default');
            }
            if (angular.isDefined(vm.form_fields) && vm.form_fields !== null) {
                if (vm.form_fields.length > 0) {
                    angular.forEach(vm.form_fields, function(fields) {
                        var temp = $builder.addFormObject('default', {
                            id: fields.id,
                            component: fields.input_types.name,
                            label: fields.label,
                            description: fields.info,
                            placeholder: '',
                            required: fields.is_required,
                            editable: false
                        });
                        vm.form_field_name[temp.id] = fields.name;
                    });
                    vm.form = $builder.forms['default'];
                }
            }
        };
        vm.uploadFiles = function(files) {
            angular.forEach(files, function(image) {
                vm.ad_image_files.push(image);
            });
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    vm.uploadAdAttachment(i, files);
                }
            }
        };
        vm.uploadAdAttachment = function(i, files) {
            Upload.upload({
                    url: '/api/v1/attachments',
                    data: {
                        file: files[i]
                    },
                    class: "Ad"
                })
                .then(function(response) {
                    vm.ad.image.push(response.data.id);
                });
        };
        vm.removeImage = function(index) {
            vm.ad.image.splice(index, 1);
            vm.ad_image_files.splice(index, 1);
        };
        vm.removeAdImage = function(index, id) {
            vm.attachment_id = id;
            AttachmentDeleteFactory.remove({
                attachmentId: vm.attachment_id
            }, function(response) {
                vm.ad_images.splice(index, 1);
            });
        };
        vm.getAdvertiserType = function() {
            AdsAdvertiserTypeFactory.get(function(response) {
                vm.advertiser_types = response.data;
            });
        };
        vm.getCategories = function() {
            vm.group_categories = [];
            var params = {};
            params.sortby = "ASC";
            params.parent_id = 0;
            params.limit = 'all';
            CategoriesFactory.get(params, function(response) {
                vm.categories = response.data;
                vm.category_metadata = response._metadata;
                vm.max = vm.category_metadata.max_level;
                vm.level = Math.ceil(12 / vm.category_metadata.max_level);
                for (var i = 0; i <= vm.max; i++) {
                    if (i === 0) {
                        vm.group_categories[i] = $filter('filter')(vm.categories, {
                            parent_id: 0
                        });
                    } else {
                        vm.group_categories[i] = [];
                    }
                }
            });
        };
        vm.adUpdate = function() {
            var flashMessage;
            vm.ad.ad_form_field = [];
            angular.forEach(vm.input, function(fields) {
                var obj = {};
                obj[vm.form_field_name[fields.id]] = fields.value;
                vm.ad.ad_form_field.push(obj);
            });
            if (vm.EditForm.$valid) {

                vm.ad.is_send_email_when_user_contact = (vm.ad.is_send_email_when_user_contact === true) ? 1 : 0;
                vm.ad.is_negotiable = (vm.ad.is_negotiable === true) ? 1 : 0;
                vm.ad.is_an_exchange_item = (vm.ad.is_an_exchange_item === true) ? 1 : 0;
                if (!angular.isString(vm.place) && vm.place !== null) {
                    vm.ad.latitude = vm.place.geometry.location.lat();
                    vm.ad.longitude = vm.place.geometry.location.lng();
                    vm.ad.location = vm.place.formatted_address;
                    angular.forEach(vm.place.address_components, function(value) {
                        if (value.types[0] === 'locality' || value.types[0] === 'administrative_area_level_2') {
                            vm.ad.city_name = value.long_name;
                        }
                        if (value.types[0] === 'administrative_area_level_1') {
                            vm.ad.state_name = value.long_name;
                        }
                        if (value.types[0] === 'country') {
                            vm.ad.country_iso2 = value.short_name;
                        }
                    });
                }
                AdFactory.update({
                    adId: $stateParams.id
                }, vm.ad, function(response) {
                    if (response.error.code === 0) {
                        flashMessage = $filter("translate")("Ad Updated Successfully");
                        flash.set(flashMessage, 'success', false);
                        $state.go('my_ads');
                    }
                });
            }
        };
        var unregisterUpdateCategory = $rootScope.$on('updateCategory', function(event, args) {
            vm.category_chosen = args.category;
            vm.selectcategory(vm.category_chosen);
        });
        $rootScope.$on("$destroy", function() {
            unregisterUpdateCategory();
        });
        vm.open = function() {
            vm.modalInstance = $uibModal.open({
                templateUrl: 'scripts/plugins/Ad/Ad/views/default/category_model.html',
                controller: 'CategoryModalInstaceController as vm',
                animation: false,
                size: 'lg',
                backdrop: 'static',
                resolve: {
                    categories: function() {
                        return vm.group_categories;
                    },
                    level: function() {
                        return vm.level;
                    },
                    max: function() {
                        return vm.max;
                    }
                }
            });
        };
        vm.index();
    });