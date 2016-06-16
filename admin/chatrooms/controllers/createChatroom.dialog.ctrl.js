(function(){
  'use strict';

  angular
    .module("chatrooms")
    .controller("createChatroomDialog", createChatroomDialog);

  function createChatroomDialog($mdDialog, Clazz, Chatroom) {
    var vm = this;
    vm.title = "create new chatroom"
    vm.action = "create"
    vm.chatroom = {};
    vm.classes = [];
    vm.searchTerm = "";

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
