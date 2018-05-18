'use strict';

describe('Controller: UsersLogutCtrl', function () {

  // load the controller's module
  beforeEach(module('olikerApp'));

  var UsersLogutCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    UsersLogutCtrl = $controller('UsersLogutCtrl', {
      $scope: scope
      // place here mocked dependencies
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(UsersLogutCtrl.awesomeThings.length).toBe(3);
  });
});
