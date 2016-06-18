(function() {
  'use strict';

  angular
    .module("unoteAdmin", ['ngResource', 'ngMaterial', 'ui.router', 'md.data.table', 'users', 'classes', 'chatrooms'])

  .config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/home/students');

    $stateProvider
      .state('home', {
        url: '/home',
        templateUrl: 'admin/home/partials/home.html',
        controllerAs: "vm"
      })
      .state('home.students', {
        url: '/students',
        templateUrl: 'admin/users/partials/students.html',
        controllerAs: 'vm',
        controller: 'studentsCtrl'
      })
      .state('home.teachers', {
        url: '/teachers',
        templateUrl: 'admin/users/partials/teachers.html',
        controllerAs: 'vm',
        controller: 'teachersCtrl'
      }).state('home.classes', {
        url: '/classes',
        templateUrl: 'admin/classes/partials/classes.html',
        controllerAs: 'vm',
        controller: 'classesCtrl'
      }).state('home.chatrooms',{
        url: '/chatrooms',
        templateUrl: "admin/chatrooms/partials/chatrooms.html",
        controllerAs: 'vm',
        controller: "chatroomsCtrl"
      }).state('auth',{
        url: '/auth',
        templateUrl: 'admin/auth/partials/auth.html'
      })
  })

  .config(['$mdIconProvider', function($mdIconProvider) {
    $mdIconProvider
      .icon("menu", "admin/css/svg/ic_menu_white_24px.svg", 24)
      .icon("add", "admin/css/svg/ic_add_white_24px.svg", 24)
      .icon("logout", "admin/css/svg/ic_power_settings_black_24px.svg", 24)
      .icon("delete", "admin/css/svg/ic_delete_black_24px.svg", 24)
      .icon("edit", "admin/css/svg/ic_mode_edit_black_24px.svg", 24)
  }])

  .config(['$mdThemingProvider', function($mdThemingProvider) {
    $mdThemingProvider.theme('default')
      .primaryPalette('brown')
      .accentPalette('red');
  }])

})();
