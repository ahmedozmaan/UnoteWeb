(function() {
  'use strict';

  angular
    .module("unoteAdmin", ['ngResource', 'ngMaterial','ui.router', 'md.data.table', 'users'])

    .config(function($stateProvider, $urlRouterProvider){

      $urlRouterProvider.otherwise('/home');

      $stateProvider
        .state('home',{
          url: '/home',
          templateUrl:'users/partials/home.html'
        })
        .state('home.students',{
          url: '/students',
          templateUrl: 'users/partials/students.html',
          controllerAs: 'vm',
          controller: 'studentsCtrl'
        })
        .state('home.teachers',{
          url: '/teachers',
          templateUrl: 'users/partials/teachers.html',
          controllerAs: 'vm',
          controller: 'teachersCtrl'
        })
    })

    .config(['$mdIconProvider', function($mdIconProvider){
      $mdIconProvider
        .icon("menu", "/css/svg/ic_menu_white_24px.svg", 24)
        .icon("add", "/css/svg/ic_add_white_24px.svg", 24)
    }])

})();
