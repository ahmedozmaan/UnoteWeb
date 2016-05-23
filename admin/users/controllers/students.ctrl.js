(function(){
  'use strict';

  angular
    .module("users")
    .controller("studentsCtrl", studentsCtrl);

  function studentsCtrl($mdDialog, User) {
    var vm = this;
    vm.students = [];
    vm.query = {
      limit: 5,
      page: 1
    };

    User.query().$promise.then(function(data) {
      vm.students = data;
    })

    vm.create = function() {
      $mdDialog.show({
        templateUrl: 'users/partials/createStudent.dialog.html',
        controller:'createStudentDialog',
        controllerAs: 'vm',
        clickOutsideToClose:true
      })
    }

  }

})();
