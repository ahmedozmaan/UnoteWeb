(function(){
  'use strict';

  angular
    .module("chatrooms")
    .factory("Chatroom", Chatroom);

  Chatroom.$inject = ['$resource'];

  function Chatroom($resource){
    return $resource('chatrooms.json', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }



})();
