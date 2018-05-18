  angular.module('olikerApp.Ad.AdReport')
      .controller('ReportModalInstanceController', function(ad, $uibModalInstance, AdReportTypesFactory, AdReportFactory, $filter, flash) {
          var vm = this;
          vm.report = {};
          vm.ad = ad;
          vm.getAdReportType = function() {
              var params = {};
              params.fields = "id,name";
              AdReportTypesFactory.get(params, function(response) {
                  vm.ad_reports = response.data;
              });
          };
          vm.adReport = function() {
              vm.ad.ad_report = [];
              if (vm.reportForm.$valid) {
                  vm.report.ad_id = ad.id;
                  AdReportFactory.create(vm.report, function(response) {
                      vm.ad.ad_report.push(response);
                      if (response.error.code === 0) {
                          var flashMessage;
                          flashMessage = $filter("translate")("Reported Successfully");
                          flash.set(flashMessage, 'success', false);
                      }
                  });
                  $uibModalInstance.close(vm.response);
              }
              vm.loader = true;
          };
          vm.cancel = function() {
              $uibModalInstance.dismiss('cancel');
          };
          vm.getAdReportType();
      });