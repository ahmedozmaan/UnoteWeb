(function() {
  'use strict';

  angular
    .module("unoteAdmin", ['ngResource', 'ngMaterial', 'ui.router', 'md.data.table', 'users', 'classes'])

  .config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/home/students');

    $stateProvider
      .state('home', {
        url: '/home',
        templateUrl: 'home/partials/home.html',
        controllerAs: "vm",
        controller: 'usersCtrl'
      })
      .state('home.students', {
        url: '/students',
        templateUrl: 'users/partials/students.html',
        controllerAs: 'vm',
        controller: 'studentsCtrl'
      })
      .state('home.teachers', {
        url: '/teachers',
        templateUrl: 'users/partials/teachers.html',
        controllerAs: 'vm',
        controller: 'teachersCtrl'
      }).state('home.classes', {
        url: '/classes',
        templateUrl: 'classes/partials/classes.html',
        controllerAs: 'vm',
        controller: 'classesCtrl'
      })
  })

  .config(['$mdIconProvider', function($mdIconProvider) {
    $mdIconProvider
      .icon("menu", "/css/svg/ic_menu_white_24px.svg", 24)
      .icon("add", "/css/svg/ic_add_white_24px.svg", 24)
  }])

  .config(['$mdThemingProvider', function($mdThemingProvider) {
    $mdThemingProvider.theme('default')
      .primaryPalette('blue')
      .accentPalette('red');
  }])

})();
