(function(){
  'use strict';

  angular
    .module("classes")
    .factory("Clazz", Clazz);

  Clazz.$inject = ['$resource'];

  function Clazz($resource){
    return $resource('http://localhost:8000/v1/classes', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
