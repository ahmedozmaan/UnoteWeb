(function() {
  'use strict';

  angular
    .module('unoteAdmin')
    .directive('toolbar', toolbar)

  function toolbar() {
    return {
      restrict: 'E',
      scope: {},
      templateUrl: 'toolbar/toolbar.html',
      controller: 'toolbarCtrl',
        controllerAs: 'vm',
    }
  }

})();
