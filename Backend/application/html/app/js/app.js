
angular.module('starter', ['ionic', 'starter.controllers','starter.services','timer','ja.qr','ngCookies','Chrono.Api'])



.config(function($httpProvider,$stateProvider, $urlRouterProvider,$ionicConfigProvider) {

$ionicConfigProvider.views.maxCache(0);


  $stateProvider

    .state('app', {
      cache: false,
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'MenuCtrl'
  })
      .state('app.login', {
        cache: false,
      url: '/login',
      views: {
        'menuContent': {
          templateUrl: 'templates/login.html',
          controller: 'LoginCtrl'
        }
      }
    })


  .state('app.browse', {
    cache: false,
      url: '/browse',
      views: {
        'menuContent': {
          templateUrl: 'templates/browse.html',
          controller: 'AppCtrl'
        }
      }
    });



  $urlRouterProvider.otherwise('/app/browse');
});
