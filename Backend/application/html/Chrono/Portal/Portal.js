angular
	.module('Chrono.Portal', [
		'ngTouch',
		'ui.router'
	])
	.config([
		'$compileProvider', '$logProvider', '$urlRouterProvider',
		function($compileProvider, $logProvider, $urlRouterProvider) {
			$compileProvider.debugInfoEnabled(true);
			$logProvider.debugEnabled(true);
			$urlRouterProvider.otherwise('/');
		}
	])
	.run([
		'$rootScope', '$state',
		function($rootScope, $state) {
			$rootScope.$state = $state;
			$rootScope.$on('$stateChangeStart', function(event, to, params) {
				if (to.redirectTo) {
					event.preventDefault();
					$state.go(to.redirectTo, params)
				}
			});
		}
	]);
