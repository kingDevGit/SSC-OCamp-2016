(function(angular) {
	'use strict';

	function ChronoApiExceptionsHandler($injector, $log, $q) {
		this.$injector = $injector;
		this.$log = $log;
		this.$q = $q;

		this.$log.debug('[ChronoApiExceptionsHandler] construct');
	}

	ChronoApiExceptionsHandler.prototype.hasException = function(exception) {
		return this.$injector.has('Chrono.Api.Exceptions.' + exception);
	};

	ChronoApiExceptionsHandler.prototype.responseError = function(response) {
		this.$log.debug('[ChronoApiExceptionsHandler.responseError]');

		var Exception;
		if (response.data && response.data.exception) {
			this.$log.debug('[ChronoApiExceptionsHandler.responseError] Exception', response.data);

			Exception = this.hasException(response.data.exception)
				? this.$injector.get('Chrono.Api.Exceptions.' + response.data.exception)
				: this.$injector.get('Chrono.Api.Exceptions.UnknownError');

			return this.$q.reject(new Exception(response.data.message));

		} else if (response.statusText) {
			this.$log.debug('[ChronoApiExceptionsHandler.responseError] Unknown Error', response);

			Exception = this.$injector.get('Chrono.Api.Exceptions.UnknownError');

			return this.$q.reject(new Exception(response.statusText));

		} else {
			this.$log.debug('[ChronoApiExceptionsHandler.responseError] Connection Error', response);

			Exception = this.$injector.get('Chrono.Api.Exceptions.ConnectionError');

			return this.$q.reject(new Exception());
		}
	};

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Exceptions.Handler', [
			'$injector', '$log', '$q',
			function($injector, $log, $q) {
				var handler = new ChronoApiExceptionsHandler($injector, $log, $q);
				return { 'responseError': handler.responseError.bind(handler) }
			}
		]);

})(angular);

