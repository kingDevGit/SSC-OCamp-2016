(function(angular) {
	'use strict';

	function ViolateSettingsException(message) {
		this.message = message;
	}

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.ViolateSettingsException', function() { return ViolateSettingsException });

})(angular);
