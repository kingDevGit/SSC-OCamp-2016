(function(angular, io) {
	'use strict';

	function ChronoApiSocket(socketHost, $log, $q) {
		this.socketHost = socketHost;
		this.$log = $log;
		this.$q = $q;

		this.$log.debug('[ChronoApiSocket] construct');

		this.deferred = null;
		this.socket = null;
		this.ready = false;
	}

	ChronoApiSocket.prototype.getAsync = function() {
		this.$log.debug('[ChronoApiSocket.getAsync]');

		if (this.ready === true && this.socket) {
			return this.$q.resolve(this.socket);
		}

		this.socket = io(this.socketHost);
		this.ready = false;

		this.$log.debug('[ChronoApiSocket.socket] create');

		var self = this;
		self.deferred = self.$q.defer();

		var onConnect = ChronoApiSocket.prototype.onConnectEventHandler.bind(self);
		var onConnectError = ChronoApiSocket.prototype.onConnectErrorEventHandler.bind(self);
		var onReconnectFailed = ChronoApiSocket.prototype.onReconnectFailedEventHandler.bind(self);

		self.socket.on('connect', onConnect);
		self.socket.on('connect_error', onConnectError);

		self.socket.on('connect', function() {
			self.socket.removeListener('connect', onConnect);
			self.socket.removeListener('connect_error', onConnectError);
		});
		self.socket.on('connect_error', function() {
			self.socket.removeListener('connect', onConnect);
			self.socket.removeListener('connect_error', onConnectError);
		});

		self.socket.on('reconnect_failed', onReconnectFailed);

		return self.deferred.promise;
	};

	ChronoApiSocket.prototype.onConnectEventHandler = function() {
		this.$log.debug('[ChronoApiSocket.onConnectEventHandler]');

		this.ready = true;
		this.deferred.resolve(this.socket);
	};

	ChronoApiSocket.prototype.onConnectErrorEventHandler = function() {
		this.$log.debug('[ChronoApiSocket.onConnectErrorEventHandler]');

		this.ready = false;
		this.deferred.reject();
	};

	ChronoApiSocket.prototype.onReconnectFailedEventHandler = function() {
		this.$log.debug('[ChronoApiSocket.onReconnectFailedEventHandler]');

		this.ready = false;
	};

	ChronoApiSocket.prototype.disconnect = function() {
		this.$log.debug('[ChronoApiSocket.disconnectAsync]');

		if (this.ready === true && this.socket) {
			this.socket.off();
			this.socket.disconnect();
			this.socket = null;
			this.ready = false;
		}
	};

	angular
		.module('Chrono.Api')
		.service('Chrono.Api.Socket', [
			'Chrono.Api.Constant.socketHost',
			'$log',
			'$q',
			ChronoApiSocket
		]);

})(angular, io);
