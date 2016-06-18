(function() {
  'use strict';

  angular
    .module('unoteAdmin')
    .directive('toolbar', toolbar)

  function toolbar() {
    return {
      restrict: 'E',
      scope: {},
      templateUrl: 'admin/toolbar/toolbar.html',
      controller: 'toolbarCtrl',
        controllerAs: 'vm',
    }
  }

})();
