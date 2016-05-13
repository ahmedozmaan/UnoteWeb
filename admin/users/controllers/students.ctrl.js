(function(){
  'use strict';

  angular
    .module("users")
    .controller("studentsCtrl", studentsCtrl);

  function studentsCtrl(User) {
    var vm = this;
    vm.students = [];
    vm.query = {
      limit: 5,
      page: 1
    };

    User.query().$promise.then(function(data) {
      vm.students = data;
    })

  }

})();
