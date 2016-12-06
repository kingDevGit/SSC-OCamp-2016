(function(angular) {
	'use strict';

	function TimerNotFoundException(message) {
		this.message = message;
	}

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.TimerNotFoundException', function() { return TimerNotFoundException });

})(angular);
