(function(){
  'use strict';

  angular
    .module("users")
    .controller("teachersCtrl", teachersCtrl);

  function teachersCtrl(User) {
    var vm = this;
    vm.teachers = [];

    User.query().$promise.then(function(data) {
      vm.teachers = data;
    })

  }

})();
