(function(angular) {
	'use strict';

	function ConnectionError(message) {
		this.message = message;
	}

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.ConnectionError', function() { return ConnectionError });

})(angular);
