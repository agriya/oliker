'use strict';

describe('Controller: UsersRegisterCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var UsersRegisterCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersRegisterCtrl = $controller('UsersRegisterCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersRegisterCtrl.awesomeThings.length).toBe(3);
  });
});
