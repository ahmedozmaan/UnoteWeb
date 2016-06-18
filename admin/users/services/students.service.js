(function(){
  'use strict';

  angular
    .module("users")
    .factory("StudentR", StudentR);

  StudentR.$inject = ['$resource'];

  function StudentR($resource){
    return $resource('http://localhost:8000/v1/users/students', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
