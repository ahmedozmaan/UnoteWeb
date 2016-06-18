(function(){
  'use strict';

  angular
    .module("users")
    .controller("updateTeacherDialog", updateTeacherDialog);

  function updateTeacherDialog($mdDialog, Teacher, TeacherR) {
    var vm = this;
    vm.title = "update Teacher";
    vm.action = "update";
    vm.teacher = Teacher;


    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      console.log(vm.student);
      $mdDialog.cancel();
    }

  }

})();
