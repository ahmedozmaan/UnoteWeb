(function(){
  'use strict';

  angular
    .module("users")
    .controller("createStudentDialog", createStudentDialog);

  function createStudentDialog($mdDialog, User) {
    var vm = this;
    vm.student = {};

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      console.log(vm.student);
      $mdDialog.cancel();
    }

  }

})();
