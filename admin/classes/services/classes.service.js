(function(){
  'use strict';

  angular
    .module("classes")
    .factory("Clazz", Clazz);

  Clazz.$inject = ['$resource'];

  function Clazz($resource){
    return $resource('classes.json', {
      id: '@id'
    }, {
      'update': {
        method: 'PUT'
      }
    });
  }

})();
