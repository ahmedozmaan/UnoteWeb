(function(){
  'use strict';

  angular
    .module("users")
    .factory("User", User);

  User.$inject = ['$resource'];

  function User($resource){
    return $resource('http://localhost:3000/users', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
