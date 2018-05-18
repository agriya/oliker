'use strict';

describe('Directive: googleRecaptcha', function () {

  // load the directive's module
  beforeEach(module('olikerApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<google-recaptcha></google-recaptcha>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the googleRecaptcha directive');
  }));
});
