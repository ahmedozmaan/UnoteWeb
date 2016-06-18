(function(){
  'use strict';

  angular
    .module("users")
    .controller("studentsCtrl", studentsCtrl);

  function studentsCtrl($mdDialog, StudentR) {
    var vm = this;
    vm.students = [];
    vm.selected = [];
    vm.query = {
      limit: 5,
      page: 1
    };

    StudentR.query().$promise.then(function(data) {
      vm.students = data;
    })

    vm.create = function() {
      $mdDialog.show({
        templateUrl: 'admin/users/partials/createStudent.dialog.html',
        controller:'createStudentDialog',
        controllerAs: 'vm',
        clickOutsideToClose:true
      })
    }

    vm.update = function(student) {
      $mdDialog.show({
        templateUrl: 'admin/users/partials/createStudent.dialog.html',
        controller:'updateStudentDialog',
        controllerAs: 'vm',
        clickOutsideToClose:true,
        locals: {
          Student : student
        }
      })
    }

    vm.delete = function(students) {
      var confirm = $mdDialog.confirm()
        .title("Delete confirmation")
        .textContent("are you sure you want to delete " + vm.selected.length + " students")
        .ariaLabel("Delete confirmation")
        .ok("Delete")
        .cancel("cancel");

      $mdDialog.show(confirm).then(function() {

      })
    }

  }

})();
