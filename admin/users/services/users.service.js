(function(){
  'use strict';

  angular
    .module("users")
    .factory("User", User);

  User.$inject = ['$resource'];

  function User($resource){
    return $resource('http://localhost:8000/users/teachers', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
