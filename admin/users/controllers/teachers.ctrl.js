(function(){
  'use strict';

  angular
    .module("users")
    .controller("teachersCtrl", teachersCtrl);

  function teachersCtrl($mdDialog, User) {
    var vm = this;
    vm.teachers = [];
    vm.selected = [];
    vm.query = {
      limit: 5,
      page: 1
    };

    User.query().$promise.then(function(data) {
      vm.teachers = data;
    })

    vm.create = function() {
      $mdDialog.show({
        templateUrl: 'users/partials/createTeacher.dialog.html',
        controller:'createTeacherDialog',
        controllerAs: 'vm',
        clickOutsideToClose:true
      })
    }

    vm.update = function(teacher) {
      $mdDialog.show({
        templateUrl: 'users/partials/createTeacher.dialog.html',
        controller:'updateTeacherDialog',
        controllerAs: 'vm',
        clickOutsideToClose:true,
        locals: {
          Teacher: teacher
        }
      })
    }

    vm.delete = function(teachers) {
      var confirm = $mdDialog.confirm()
        .title("Delete confirmation")
        .textContent("are you sure you want to delete " + teachers.length + " teachers")
        .ariaLabel("Delete confirmation")
        .ok("Delete")
        .cancel("cancel");

      $mdDialog.show(confirm).then(function() {

      })
    }

  }

})();
