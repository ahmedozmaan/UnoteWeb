(function(){
  'use strict';

  angular
    .module("users")
    .controller("teachersCtrl", teachersCtrl);

  function teachersCtrl($mdDialog, User) {
    var vm = this;
    vm.teachers = [];
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

  }

})();
