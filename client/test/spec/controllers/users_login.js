'use strict';

describe('Controller: UsersLoginCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var UsersLoginCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersLoginCtrl = $controller('UsersLoginCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersLoginCtrl.awesomeThings.length).toBe(3);
  });
});
