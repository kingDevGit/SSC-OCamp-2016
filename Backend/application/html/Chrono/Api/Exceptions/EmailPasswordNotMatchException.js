(function(angular) {
	'use strict';

	function EmailPasswordNotMatchException(message) {
		this.message = message;
	}

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.EmailPasswordNotMatchException', function() { return EmailPasswordNotMatchException });

})(angular);
