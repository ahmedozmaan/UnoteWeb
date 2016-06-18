(function() {
  'use strict';

  angular
    .module('classes')
    .controller('classesCtrl', classesCtrl)

  function classesCtrl($mdDialog, Clazz) {
    var vm = this;
    vm.classes = [];
    vm.selected = [];
    vm.query = {
      limit: 5,
      page: 1
    }

    Clazz.query().$promise.then(function(result) {
      vm.classes = result;
    })

    vm.create = function() {
      $mdDialog.show({
        templateUrl: 'admin/classes/partials/createClass.dialog.html',
        controller:'createClassDialog',
        controllerAs: 'vm',
        clickOutsideToClose:true
      })
    }

  }
})();
