(function(){
  'use strict';

  angular
    .module("users")
    .controller("createStudentDialog", createStudentDialog);

  function createStudentDialog($mdDialog, Clazz, StudentR) {
    var vm = this;
    vm.title = "update student";
    vm.action = "create";
    vm.student = {};
    vm.classes = [];

    Clazz.query().$promise.then(function(result) {
      vm.classes = result;
    })

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      StudentR.save(vm.student);
      console.log(vm.student);
      $mdDialog.cancel();
    }

  }

})();
