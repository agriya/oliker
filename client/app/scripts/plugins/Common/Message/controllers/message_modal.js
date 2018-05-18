angular.module('olikerApp.Common.Message')
    .controller('MessageModalInstanceController', function(ad_id, other_user_id, $uibModalInstance, MessagesFactory, Upload, $filter, flash) {
        var vm = this;
        vm.message = {};
        vm.message.image = [];
        vm.message_images = [];
        vm.save_button = true;
        vm.uploadFiles = function(files) {
            angular.forEach(files, function(image) {
                vm.message_images.push(image);
            });
            if (files && files.length) {
                for (var i = 0; i < files.length; i++) {
                    vm.uploadMessageAttachment(i, files);
                }
            }
        };
        vm.uploadMessageAttachment = function(i, files) {
            Upload.upload({
                    url: '/api/v1/attachments',
                    data: {
                        file: files[i]
                    },
                    class: "Message"
                })
                .then(function(response) {
                    vm.message.image.push(response.data.attachment);
                });
            vm.save_button = false;
        };
        vm.removeImage = function(index) {
            vm.message.image.splice(index, 1);
            vm.message_images.splice(index, 1);
        };
        vm.saveMessage = function() {
            if (vm.messageForm.$valid) {
                vm.message.ad_id = ad_id;
                vm.message.other_user_id = other_user_id;
                MessagesFactory.create(vm.message, function(response) {
                    if (response.error.code === 0) {
                        var flashMessage;
                        flashMessage = $filter("translate")("Message Sent Successfully");
                        flash.set(flashMessage, 'success', false);
                    }
                });
                $uibModalInstance.close(vm.response);
            }
        };
        vm.cancel = function() {
            $uibModalInstance.dismiss('cancel');
        };
    });