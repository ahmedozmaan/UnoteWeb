(function() {
  "use strict";

  angular
    .module("chatrooms")
    .controller("chatroomsCtrl", chatroomsCtrl)

  function chatroomsCtrl($mdDialog, Chatroom) {
    var vm = this;
    vm.chatrooms = [];

    Chatroom.query().$promise.then(function(result) {
      vm.chatrooms = result;
    })

    vm.create = function() {
      $mdDialog.show({
        templateUrl: 'chatrooms/partials/chatroom.dialog.html',
        controller: 'createChatroomDialog',
        controllerAs: 'vm',
        clickOutsideToClose: true
      })
    }

    vm.update = function(chatroom) {
      $mdDialog.show({
        templateUrl: 'chatrooms/partials/chatroom.dialog.html',
        controller: 'updateChatroomDialog',
        controllerAs: 'vm',
        clickOutsideToClose: true,
        locals: {
          chatroom: chatroom
        }
      })
    }

    vm.delete = function(chatroom){
      var confirm = $mdDialog.confirm()
        .title("Delete confirmation")
        .textContent("are you sure you want to delete " + chatroom.name + " chatroom")
        .ariaLabel("Delete confirmation")
        .ok("Delete")
        .cancel("cancel");
      $mdDialog.show(confirm).then(function(){

      })
    }

  }

})();
