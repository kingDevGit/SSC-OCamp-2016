(function(angular) {
	'use strict';

	function UnknownError(message) {
		this.message = message;
	}

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.UnknownError', function() { return UnknownError });

})(angular);
