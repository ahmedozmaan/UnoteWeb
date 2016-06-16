(function(){
  'use strict';

  angular
    .module("classes")
    .factory("Clazz", Clazz);

  Clazz.$inject = ['$resource'];

  function Clazz($resource){
    return $resource('http://localhost:3000/classes', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
