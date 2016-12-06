(function(angular) {
	'use strict';

	function LoginController(api, $log, $scope, $window) {
		this.chrono = api;
		this.$log = $log;
		this.$scope = $scope;
		this.$window = $window;

		this.$log.debug('[LoginController] construct');

		this.loginFields = { email: '', password: '', token: '' };
		this.token = '';
		this.ready = false;
		this.exception = '';
	}

	LoginController.prototype.loginByEmailPassword = function() {
		this.$log.debug('[LoginController.loginByEmailPassword]');

		if (!this.loginFields.email || !this.loginFields) {
			return this.$window.alert('Email & Password');
		}

		var self = this;
		self.chrono.auth.loginAsync(self.loginFields.email, self.loginFields.password)
			.then(function() {
				self.$log.debug('[LoginController.loginByEmailPassword] OK');

				self.ready = true;
				self.token = self.chrono.auth.getToken();
				self.exception = '';
			})
			.catch(function(exception) {
				self.$log.debug('[LoginController.loginByEmailPassword] Error', exception);

				self.ready = false;
				self.token = '';

				if (exception instanceof self.chrono.exceptions.EmailPasswordNotMatchException) {
					self.exception = 'EmailPasswordNotMatchException';
				} else if (exception instanceof self.chrono.exceptions.ConnectionError) {
					self.exception = 'ConnectionError';
				} else {
					self.exception = 'UnknownError (See console)';
				}
			});
	};

	LoginController.prototype.loginByToken = function() {
		this.$log.debug('[LoginController.loginByToken]');

		if (!this.loginFields.token) {
			return this.$window.alert('Token');
		}

		var self = this;
		self.chrono.auth.verifyAsync(self.loginFields.token)
			.then(function() {
				self.$log.debug('[LoginController.loginByToken] OK');

				self.ready = true;
				self.token = self.chrono.auth.getToken();
				self.exception = '';
			})
			.catch(function(exception) {
				self.$log.debug('[LoginController.loginByToken] Error', exception);

				self.ready = false;
				self.token = '';

				if (exception instanceof self.chrono.exceptions.TokenNotValidException) {
					self.exception = 'TokenNotValidException';
				} else if (exception instanceof self.chrono.exceptions.ConnectionError) {
					self.exception = 'ConnectionError';
				} else {
					self.exception = 'UnknownError (See console)';
				}
			});
	};

	LoginController.prototype.logout = function() {
		this.$log.debug('[LoginController.logout]');

		if (!this.ready) {
			return this.$window.alert('Login first');
		}

		var self = this;
		self.chrono.auth.logoutAsync()
			.then(function() {
				self.$log.debug('[LoginController.logout] OK');

				self.ready = false;
				self.token = '';
				self.exception = '';
			})
			.catch(function(exception) {
				self.$log.debug('[LoginController.logout] Error', exception);

				self.ready = false;
				self.token = '';

				if (exception instanceof self.chrono.exceptions.ConnectionError) {
					self.exception = 'ConnectionError';
				} else {
					self.exception = 'UnknownError (See console)';
				}
			});
	};

	angular
		.module('Chrono.Demo')
		.controller('Chrono.Demo.LoginController', [
			'Chrono.Api', '$log', '$scope', '$window',
			LoginController
		]);

})(angular);
