angular
	.module('Chrono.Portal')
	.config(['$stateProvider', function($stateProvider) {
		$stateProvider
			.state('dashboard', {
				url: '/',
				redirectTo: 'dashboard.panel',
				resolve: {},
				views: {
					'': {
						controller: 'DashboardController',
						controllerAs: 'dashboardCtrl',
						templateUrl: 'dashboard.html'
					}
				}
			});
	}])
	.controller('DashboardController', [
		'$scope', '$log',
		function($scope, $log) {

		}
	]);
