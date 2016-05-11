(function(){
  'use strict';

  angular
    .module("users")
    .controller("studentsCtrl", studentsCtrl);

  function studentsCtrl(User) {
    var vm = this;

    vm.students = [];

    User.query().$promise.then(function(data) {
      vm.students = data;
      console.log(vm.students);
      console.log("hellooo");
    })
  }

})();
