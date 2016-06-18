(function(){
  'use strict';

  angular
    .module("users")
    .controller("updateStudentDialog", updateStudentDialog);

  function updateStudentDialog($mdDialog, Student, Clazz, StudentR) {
    var vm = this;
    vm.title = "update student";
    vm.action = "update";
    vm.student = Student;
    vm.classes = [];

    Clazz.query().$promise.then(function(result) {
      vm.classes = result;
    })

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      console.log(vm.student);
      $mdDialog.cancel();
    }

  }

})();
