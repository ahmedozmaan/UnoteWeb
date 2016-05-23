(function() {
  'use strict';

  angular
    .module("unoteAdmin")
    .controller("sidenavCtrl", sidenavCtrl)

  function sidenavCtrl($mdSidenav) {
    var vm = this;
    vm.urls = [
      {"name": "students", "state": ".students"},
      {"name": "teachers", "state": ".teachers"},
      {"name": "classes", "state": ".classes"},
      {"name": "chatrooms", "state": ".chatrooms"}
    ];

    vm.close = function() {
      $mdSidenav('left').toggle();
    }

  }

})();
