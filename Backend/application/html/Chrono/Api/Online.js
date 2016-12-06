(function(angular) {
	'use strict';

	function ChronoApiOnline(auth, fetcher, socket, host, TokenNotValidException, $http, $log, $q) {
		this.auth = auth;
		this.fetcher = fetcher;
		this.socket = socket;
		this.host = host;

		this.TokenNotValidException = TokenNotValidException;

		this.$http = $http;
		this.$log = $log;
		this.$q = $q;

		this.$log.debug('[ChronoApiOnline] construct');

		this.$scope = null;
		this.sid = null;
		this.admin = false;
	}

	ChronoApiOnline.prototype.setScope = function($scope) {
		this.$scope = $scope;
		this.fetcher.setScope(this.$scope);
	};

	ChronoApiOnline.prototype.setAdmin = function(admin) {
		this.admin = !!admin;
	};

	ChronoApiOnline.prototype.onlineAsync = function() {
		this.$log.debug('[ChronoApiOnline.onlineAsync]');

		var self = this;
		return self.socket.getAsync()
			.then(function(socket) {
				self.$log.debug('[ChronoApiOnline.onlineAsync] OK 1');

				self.sid = socket.id;
				self.handleSocket(socket);

				return self.onlineRequestAsync();
			})
			.then(function() {
				self.$log.debug('[ChronoApiOnline.onlineAsync] OK 2');

				return self.fetcher.updateAsync();
			});
	};

	ChronoApiOnline.prototype.offlineAsync = function() {
		this.$log.debug('[ChronoApiOnline.offlineAsync]');

		var self = this;
		return self.offlineRequestAsync()
			.then(function() {
				self.$log.debug('[ChronoApiOnline.offlineAsync] OK');

				self.sid = null;
				self.socket.disconnect();
				self.fetcher.reset();

				return self.$q.resolve();
			});
	};

	ChronoApiOnline.prototype.handleSocket = function(socket) {
		this.$log.debug('[ChronoApiOnline.handleSocket]');

		var self = this;
		self.fetcher.handleSocket(socket);

		['disconnect', 'reconnect_attempt', 'reconnect_error', 'reconnect_failed'].forEach(function(e) {
			socket.on(e, function() {
				self.$log.debug('[ChronoApiOnline.handleSocket] on ' + e);

				if (self.$scope) {
					self.$scope.$emit(e);
					self.$scope.$apply();
				}
			});
		});

		socket.on('reconnect', function() {
			self.$log.debug('[ChronoApiOnline.handleSocket] on reconnect');

			self.sid = socket.id;
			self.onlineRequestAsync()
				.then(function() {
					if (self.$scope) {
						self.$scope.$emit('reconnect');
						self.fetcher.updateAsync();
					}
				});
		});
	};

	ChronoApiOnline.prototype.onlineRequestAsync = function() {
		this.$log.debug('[ChronoApiOnline.onlineRequestAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		return self.$http
			.post(self.host + '/online', {
				'sid': '/#' + self.sid,
				'admin': self.admin
			}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiOnline.onlineRequestAsync] OK');
				return self.$q.resolve();
			});
	};

	ChronoApiOnline.prototype.offlineRequestAsync = function() {
		this.$log.debug('[ChronoApiOnline.offlineRequestAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		return self.$http
			.post(self.host + '/offline', {
				'sid': '/#' + self.sid
			}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiOnline.offlineRequestAsync] OK');
				return self.$q.resolve();
			});
	};

	angular
		.module('Chrono.Api')
		.service('Chrono.Api.Online', [
			'Chrono.Api.Auth',
			'Chrono.Api.Fetcher',
			'Chrono.Api.Socket',
			'Chrono.Api.Constant.host',
			'Chrono.Api.Exceptions.TokenNotValidException',
			'$http',
			'$log',
			'$q',
			ChronoApiOnline
		]);

})(angular);
