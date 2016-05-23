(function() {
  'use strict';

  angular
    .module("unoteAdmin")
    .controller("toolbarCtrl", toolbarCtrl)

  function toolbarCtrl($mdSidenav) {
    var vm = this;

    vm.toggle = function() {
      $mdSidenav('left').toggle();
    }

  }

})();
