(function(){
  'use strict';

  angular
    .module("chatrooms")
    .controller("updateChatroomDialog", updateChatroomDialog);

  function updateChatroomDialog($mdDialog, Clazz, Chatroom, chatroom) {
    var vm = this;
    vm.title = "update chatroom";
    vm.action = "update"
    vm.chatroom = {};
    vm.chatroom.name = chatroom.name;
    vm.chatroom.classes = chatroom.classes;

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
