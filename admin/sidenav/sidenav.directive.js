(function() {
  'use strict';

  angular
    .module('unoteAdmin')
    .directive('sidenav', sidenav)

  function sidenav() {
    return {
      restrict: 'E',
      scope: {},
      templateUrl: 'admin/sidenav/sidenav.html',
      controller: 'sidenavCtrl',
        controllerAs: 'vm',
    }
  }

})();
