(function() {
  'use strict';

  angular
    .module("unoteAdmin", ['ngMaterial','ui.router'])

    .config(function($stateProvider, $urlRouterProvider){

      $urlRouterProvider.otherwise('/home');

      $stateProvider
        .state('home',{
          url: '/home',
          templateUrl:'partials/home.html'
        })
        .state('home.students',{
          url: '/students',
          templateUrl: 'partials/students.html'
        })
        .state('home.teachers',{
          url: '/teachers',
          templateUrl: 'partials/teachers.html'
        })
    })

    .config(['$mdIconProvider', function($mdIconProvider){
      $mdIconProvider.
        icon("menu", "/css/svg/ic_menu_white_24px.svg", 24)
    }])

})();
