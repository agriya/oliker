'use strict';

describe('Controller: UsersSettingsCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var UsersSettingsCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersSettingsCtrl = $controller('UsersSettingsCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersSettingsCtrl.awesomeThings.length).toBe(3);
  });
});
