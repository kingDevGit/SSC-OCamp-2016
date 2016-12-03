(function(angular) {
	'use strict';

	function ChronoApiFetcher(auth, socket, Notification, Player, Union, host, TokenNotValidException, $http, $log, $q) {
		this.auth = auth;
		this.socket = socket;

		this.Notification = Notification;
		this.Player = Player;
		this.Union = Union;
		this.host = host;

		this.TokenNotValidException = TokenNotValidException;

		this.$http = $http;
		this.$log = $log;
		this.$q = $q;

		this.$log.debug('[ChronoApiFetcher] construct');

		this.ready = false;
		this.$scope = null;

		this.player = null;
		this.notifications = [];
		this.settings = {};
	}

	ChronoApiFetcher.prototype.reset = function() {
		this.ready = false;
		this.$scope = null;

		this.player = null;
		this.notifications = [];
		this.settings = {};
	};

	ChronoApiFetcher.prototype.setScope = function($scope) {
		this.$scope = $scope;
	};

	ChronoApiFetcher.prototype.fetchPlayer = function() {
		return this.ready && this.player
			? angular.copy(this.player)
			: null;
	};

	ChronoApiFetcher.prototype.fetchUnion = function() {
		return this.ready && this.player && this.player.union
			? angular.copy(this.player.union)
			: null;
	};

	ChronoApiFetcher.prototype.fetchNotifications = function() {
		return this.ready
			? angular.copy(this.notifications)
			: [];
	};

	ChronoApiFetcher.prototype.fetchSettings = function() {
		return this.ready
			? angular.copy(this.settings)
			: {};
	};

	ChronoApiFetcher.prototype.updateAsync = function() {
		this.$log.debug('[ChronoApiFetcher.updateAsync]');

		var self = this;
		return self.$q
			.all([
				self.updatePlayerAsync(),
				self.updateSettingsAsync()
			])
			.then(function() {
				self.$log.debug('[ChronoApiFetcher.updateAsync] OK 1');
				return self.updateNotificationAsync()
			})
			.then(function() {
				self.$log.debug('[ChronoApiFetcher.updateAsync] OK 2');
				self.ready = true;
			});
	};

	ChronoApiFetcher.prototype.handleSocket = function(socket) {
		this.$log.debug('[ChronoApiFetcher.handleSocket]');

		var self = this;
		socket.on('player', function(player) {
			self.$log.debug('[ChronoApiFetcher.handleSocket] on player');
			self.setPlayer(player);

			self.$scope && self.$scope.$apply();
		});

		socket.on('union', function(union) {
			self.$log.debug('[ChronoApiFetcher.handleSocket] on union');
			self.setUnion(union);

			self.$scope && self.$scope.$apply();
		});

		socket.on('notification', function(notification) {
			self.$log.debug('[ChronoApiFetcher.handleSocket] on notification');
			self.addNotifications([notification]);

			self.$scope && self.$scope.$apply();
		});

		socket.on('settings', function(settings) {
			self.$log.debug('[ChronoApiFetcher.handleSocket] on settings');
			self.setSettings(settings);

			self.$scope && self.$scope.$apply();
		});
	};

	ChronoApiFetcher.prototype.updatePlayerAsync = function() {
		this.$log.debug('[ChronoApiFetcher.updatePlayerAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		return self.$http
			.get(self.host + '/me', {
				headers: { 'Chrono-Token': token }
			})
			.then(function(response) {
				self.$log.debug('[ChronoApiFetcher.updatePlayerAsync] OK');
				self.setPlayer(response.data);
				return self.fetchPlayer();
			});
	};

	ChronoApiFetcher.prototype.updateUnionAsync = function() {
		this.$log.debug('[ChronoApiFetcher.updateUnionAsync]');

		return this.updatePlayerAsync()
			.then(function() {
				self.$log.debug('[ChronoApiFetcher.updateUnionAsync] OK');
				self.setUnion(angular.copy(self.player.union));
				return self.fetchUnion();
			});
	};

	ChronoApiFetcher.prototype.updateNotificationAsync = function(min, max) {
		this.$log.debug('[ChronoApiFetcher.updateNotificationAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		var params = {};
		if (self.ready) {
			if (min) {
				params.min = min instanceof Date
					? Math.floor(min.getTime() / 1000)
					: min;
			}

			if (max) {
				params.max = max instanceof Date
					? Math.floor(max.getTime() / 1000)
					: max;
			}

			if (!min && !max && self.notifications.length > 0) {
				var notification = self.notifications[self.notifications.length - 1];
				params.min = Math.floor(notification.created_at.getTime() / 1000) + 1;
			}
		}

		return self.$http
			.get(self.host + '/me/notifications', {
				headers: { 'Chrono-Token': token },
				params: params
			})
			.then(function(response) {
				self.$log.debug('[ChronoApiFetcher.updateNotificationAsync] OK');
				self.addNotifications(response.data);
				return self.fetchUnion();
			});
	};

	ChronoApiFetcher.prototype.updateSettingsAsync = function() {
		this.$log.debug('[ChronoApiFetcher.updateSettingsAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		return self.$http
			.get(self.host + '/settings', {
				headers: { 'Chrono-Token': token }
			})
			.then(function(response) {
				self.$log.debug('[ChronoApiFetcher.updateSettingsAsync] OK');
				self.setSettings(response.data);
				return self.fetchSettings();
			});
	};

	ChronoApiFetcher.prototype.setPlayer = function(p) {
		var player = new this.Player();
		player.hydrate(p);

		if (!this.player || this.player.id === player.id) {
			this.player = player;
			this.$scope && this.$scope.$emit('player', this.player);
		}
	};

	ChronoApiFetcher.prototype.setUnion = function(u) {
		var union = new this.Union();
		union.hydrate(u);

		if (this.player && (!this.player.union || this.player.union.id === union.id)) {
			this.player.union = union;
			this.$scope && this.$scope.$emit('union', this.player.union);
		}
	};

	ChronoApiFetcher.prototype.addNotifications = function(ns) {
		var self = this;
		var ids = self.notifications.map(function(notification) {
			return notification.id;
		});

		ns.forEach(function(n) {
			var notification = new self.Notification();
			notification.hydrate(n);

			if (self.player && notification.to_player === self.player.id && ids.indexOf(notification.id) === -1) {
				self.notifications.push(notification);
				self.$scope && self.$scope.$emit('notification', notification);
			}
		});
	};

	ChronoApiFetcher.prototype.setSettings = function(s) {
		this.settings = s;
		this.$scope && this.$scope.$emit('settings', this.settings);
	};

	angular
		.module('Chrono.Api')
		.service('Chrono.Api.Fetcher', [
			'Chrono.Api.Auth',
			'Chrono.Api.Socket',
			'Chrono.Api.Class.Notification',
			'Chrono.Api.Class.Player',
			'Chrono.Api.Class.Union',
			'Chrono.Api.Constant.host',
			'Chrono.Api.Exceptions.TokenNotValidException',
			'$http',
			'$log',
			'$q',
			ChronoApiFetcher
		]);

})(angular);
