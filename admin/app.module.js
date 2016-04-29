(function() {
  'use strict';

  angular
    .module("unoteAdmin", ['ngMaterial','ui.router'])

    .config(function($stateProvider, $urlRouterProvider){
      $stateProvider
        .state('home.students',{
          url: 'students',
          template: 'TODO'
        })
        .state('home.teachers',{
          url: 'teachers',
          template: 'TODO'
        })
    })

})();
