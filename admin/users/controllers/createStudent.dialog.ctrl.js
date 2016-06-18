(function(){
  'use strict';

  angular
    .module("users")
    .controller("createStudentDialog", createStudentDialog);

  function createStudentDialog($mdDialog, User, Clazz) {
    var vm = this;
    vm.title = "update student";
    vm.action = "update";
    vm.student = {};
    vm.classes = [];

    Clazz.query().$promise.then(function(result) {
      vm.classes = result;
    })

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      $mdDialog.cancel();
    }

  }

})();
