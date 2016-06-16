(function(){
  'use strict';

  angular
    .module("chatrooms")
    .factory("Chatroom", Chatroom);

  Chatroom.$inject = ['$resource'];

  function Chatroom($resource){
    return $resource('http://localhost:3000/chatrooms', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }



})();
