(function(){
  'use strict';

  angular
    .module("users")
    .controller("createTeacherDialog", createTeacherDialog);

  function createTeacherDialog($mdDialog, User) {
    var vm = this;
    vm.title = "Register new Teacher";
    vm.action = "create";
    vm.teacher = {};

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      console.log(vm.teacher);
      $mdDialog.cancel();
    }

  }

})();
