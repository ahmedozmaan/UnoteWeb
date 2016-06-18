(function(){
  'use strict';

  angular
    .module("users")
    .factory("TeacherR", TeacherR);

  TeacherR.$inject = ['$resource'];

  function TeacherR($resource){
    return $resource('http://localhost:8000/v1/users/teachers', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
