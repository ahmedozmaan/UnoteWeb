(function(){
  'use strict';

  angular
    .module("classes")
    .controller("createClassDialog", createClassDialog);

  function createClassDialog($mdDialog, Clazz) {
    var vm = this;
    vm.class = {};

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      console.log(vm.class);
      $mdDialog.cancel();
    }

  }

})();
