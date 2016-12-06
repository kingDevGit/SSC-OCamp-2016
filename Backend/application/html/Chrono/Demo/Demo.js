(function(angular) {
	'use strict';

	angular
		.module('Chrono.Demo', [
			'Chrono.Api'
		])
		.config([
			'$compileProvider', '$logProvider',
			function($compileProvider, $logProvider) {
				$compileProvider.debugInfoEnabled(true);
				$logProvider.debugEnabled(true);
			}
		]);

})(angular);
