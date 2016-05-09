(function(){
  'use strict';

  angular
    .module("users")
    .factory("User", User);

  User.$inject = ['$resource'];

  function User($resource){
    return $resource('users.json', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
