(function(angular) {
	'use strict';

	function ChronoApiAuth(host, ConnectionError, $http, $log) {
		this.host = host;
		this.ConnectionError = ConnectionError;

		this.$http = $http;
		this.$log = $log;

		this.$log.debug('[ChronoApiAuth] construct');

		this.token = '';
		this.ready = false;
	}

	ChronoApiAuth.prototype.loginAsync = function(email, password) {
		this.$log.debug('[ChronoApiAuth.loginAsync]');

		var self = this;

		return self.$http
			.post(self.host + '/auth/login', {
				'email': email,
				'password': password
			})
			.then(function(response) {
				self.$log.debug('[ChronoApiAuth.loginAsync] OK');

				var session = response.data || {};

				return self.verifyAsync(session.token);
			});
	};

	ChronoApiAuth.prototype.verifyAsync = function(token) {
		this.$log.debug('[ChronoApiAuth.verifyAsync]');

		var self = this;

		return self.$http
			.get(self.host + '/auth/verify', {
				headers: { 'Chrono-Token': token }
			})
			.then(function(response) {
				self.$log.debug('[ChronoApiAuth.verifyAsync] OK');

				var session = response.data || {};

				self.ready = true;
				self.token = session.token || '';

				return true;
			})
			.catch(function(exception) {
				self.$log.debug('[ChronoApiAuth.verifyAsync] Failed');

				if (!(exception instanceof self.ConnectionError)) {
					self.ready = false;
					self.token = '';
				}

				throw exception;
			});
	};

	ChronoApiAuth.prototype.logoutAsync = function() {
		this.$log.debug('[ChronoApiAuth.logoutAsync]');

		var self = this;
		var token = self.getToken();

		return self.$http
			.post(self.host + '/auth/logout', {}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiAuth.logoutAsync] OK');

				self.ready = false;
				self.token = '';
			});
	};

	ChronoApiAuth.prototype.getToken = function() {
		return this.ready && this.token ? this.token : null;
	};

	angular
		.module('Chrono.Api')
		.service('Chrono.Api.Auth', [
			'Chrono.Api.Constant.host',
			'Chrono.Api.Exceptions.ConnectionError',
			'$http',
			'$log',
			ChronoApiAuth
		]);

})(angular);
