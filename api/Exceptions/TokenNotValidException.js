(function(angular) {
	'use strict';

	function TokenNotValidException(message) {
		this.message = message;
	}

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.TokenNotValidException', function() { return TokenNotValidException });

})(angular);
