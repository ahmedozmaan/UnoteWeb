(function() {
  'use strict';

  angular
    .module('unoteAdmin')
    .directive('sidenav', sidenav)

  function sidenav() {
    return {
      restrict: 'E',
      templateUrl: 'sidenav/sidenav.html',
    //  controllerAs: 'vm',
      controller: function() {
          var vm = this;
          vm.urls = [
            {"name": "Users"},
            {"name": "classes"},
            {"name": "chatrooms"}
          ];
        }
    }
  }

})();
