(function() {
    "use strict";

    angular
      .module("chatrooms")
      .controller("chatroomsCtrl", chatroomsCtrl)

    function chatroomsCtrl($mdDialog, Chatroom) {
      var vm = this;
      vm.classes = [];
      vm.chatrooms = [];
      vm.chatroom;

      Chatroom.query().$promise.then(function(result) {
        vm.classes = result;
        vm.chatrooms = chatrooms(vm.classes)
      })

      vm.create = function() {
        $mdDialog.show({
          templateUrl: 'chatrooms/partials/chatroom.dialog.html',
          controller:'createChatroomDialog',
          controllerAs: 'vm',
          clickOutsideToClose:true
        })
      }

      vm.update = function() {
        $mdDialog.show({
          templateUrl: 'chatrooms/partials/chatroom.dialog.html',
          controller:'updateChatroomDialog',
          controllerAs: 'vm',
          clickOutsideToClose:true
        })
      }

      function chatrooms(array) {
        var unique = [];
        for (var x in array) {
          if (array[x].name && unique.indexOf(array[x].name) === -1 ) {
              unique.push(array[x].name)
            }
          }
          return unique;
        }

      }

    })();
