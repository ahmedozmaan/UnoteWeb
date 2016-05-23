(function() {
  'use strict';

  angular
    .module('unoteAdmin')
    .directive('sidenav', sidenav)

  function sidenav() {
    return {
      restrict: 'E',
      scope: {},
      templateUrl: 'sidenav/sidenav.html',
      controller: 'sidenavCtrl',
        controllerAs: 'vm',
    }
  }

})();
