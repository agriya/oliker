'use strict';

describe('Controller: PageViewCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var PageViewCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    PageViewCtrl = $controller('PageViewCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(PageViewCtrl.awesomeThings.length).toBe(3);
  });
});
