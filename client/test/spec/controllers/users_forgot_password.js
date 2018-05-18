'use strict';

describe('Controller: UsersForgotPasswordCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var UsersForgotPasswordCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersForgotPasswordCtrl = $controller('UsersForgotPasswordCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersForgotPasswordCtrl.awesomeThings.length).toBe(3);
  });
});
