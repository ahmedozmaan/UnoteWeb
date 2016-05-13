(function() {
  'use strict';

  angular
    .module("unoteAdmin")
    .controller("sidenavCtrl", sidenavCtrl)

  function sidenavCtrl() {
    var vm = this;
    vm.urls = [
      {"name": "Users"},
      {"name": "classes"},
      {"name": "chatrooms"}
    ];

  }

})();
