(function(){
  'use strict';

  angular
    .module("chatrooms")
    .controller("updateChatroomDialog", updateChatroomDialog);

  function updateChatroomDialog($mdDialog, Clazz, Chatroom) {
    var vm = this;
    vm.chatroom = {};
    vm.classes = [];

    Clazz.query().$promise.then(function(result) {
      vm.classes = result;
    })

    vm.cancel = function() {
      $mdDialog.cancel();
    }

    vm.save = function() {
      console.log(vm.classes);
      $mdDialog.cancel();
    }

  }

})();
