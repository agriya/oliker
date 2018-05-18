'use strict';

describe('Controller: UserActivationCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var UserActivationCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UserActivationCtrl = $controller('UserActivationCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UserActivationCtrl.awesomeThings.length).toBe(3);
  });
});
