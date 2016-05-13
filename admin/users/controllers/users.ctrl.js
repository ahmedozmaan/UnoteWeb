(function() {
  'use strict';

  angular
    .module("users")
    .controller("usersCtrl", usersCtrl)

  function usersCtrl($location, $mdSidenav) {
    var vm = this;
    vm.index = 0;

    vm.tabs = [
      {"name": "student", "url": "/home/students", "state": ".students"},
      {"name": "teacher", "url": "/home/teachers", "state": ".teachers"}
    ];

    vm.selectedIndex = function() {
      var url = $location.path();
      for (var i in vm.tabs) {
        if(url == vm.tabs[i].url){
          console.log("yeey");
          vm.index = i;
        }
      }
    }

    vm.toggle = function() {
      $mdSidenav('left').toggle();
    }

  }

})();
