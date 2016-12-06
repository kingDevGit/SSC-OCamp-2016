(function(angular) {
	'use strict';

	function PlayerController(api, $interval, $log, $scope, $window) {
		this.chrono = api;
		this.$interval = $interval;
		this.$log = $log;
		this.$scope = $scope;
		this.$window = $window;

		this.$log.debug('[PlayerController] construct');

		this.isLoading = true;
		this.interval = null;
		this.events = [];

		this.player = null;
		this.union = null;
		this.primaryTimer = null;
		this.secondaryTimer = null;
		this.notifications = [];
		this.settings = {};

		this.init();

		var self = this;
		self.isBomb = function(timer) {
			return timer instanceof self.chrono.class.Bomb;
		};
	}

	PlayerController.prototype.init = function() {
		this.$log.debug('[PlayerController] init');

		this.isLoading = true;

		var self = this;
		self.chrono.auth
			.verifyAsync('Auats8oUCAKFWSrH64IYkgiVZRUEBKqceBGGK4BnFl2Beujkz2iUiBPitJX5unhJZXqJ116Gn9iCECWd')
			.then(function() {
				self.$log.debug('[PlayerController.init] OK 1');
				self.chrono.online.setScope(self.$scope.$new());
				return self.chrono.online.onlineAsync();
			})
			.then(function() {
				self.$log.debug('[PlayerController.init] OK 2');

				self.isLoading = false;
				self.events.push('Online success');

				self.handleEvent();
				self.player = self.chrono.fetcher.fetchPlayer();
				self.primaryTimer = self.player.getPrimaryTimer();
				self.secondaryTimer = self.player.getSecondaryTimer();
				self.union = self.chrono.fetcher.fetchUnion();
				self.notifications = self.chrono.fetcher.fetchNotifications();
				self.settings = self.chrono.fetcher.fetchSettings();

				self.autoApply();
			});
	};

	PlayerController.prototype.handleEvent = function() {
		this.$log.debug('[PlayerController] handleEvent');

		var self = this;
		self.$scope.$on('disconnect', function() {
			self.$log.debug('[PlayerController] on disconnect');
			self.events.push('Disconnected');
		});

		self.$scope.$on('reconnect', function() {
			self.$log.debug('[PlayerController] on reconnect');
			self.events.push('Reconnect success');
		});

		self.$scope.$on('reconnect_attempt', function() {
			self.$log.debug('[PlayerController] on reconnect_attempt');
			self.events.push('Reconnecting...');
		});

		self.$scope.$on('reconnect_error', function() {
			self.$log.debug('[PlayerController] on reconnect_error');
			self.events.push('Reconnect error');
		});

		self.$scope.$on('reconnect_failed', function() {
			self.$log.debug('[PlayerController] on reconnect_failed');
			self.events.push('Reconnect failed');
		});

		self.$scope.$on('player', function(event) {
			self.$log.debug('[PlayerController] on player');

			event.stopPropagation();

			self.events.push('Player updated');
			self.player = self.chrono.fetcher.fetchPlayer();
			self.primaryTimer = self.player.getPrimaryTimer();
			self.secondaryTimer = self.player.getSecondaryTimer();
		});

		self.$scope.$on('union', function(event) {
			self.$log.debug('[PlayerController] on union');

			event.stopPropagation();

			self.events.push('Union updated');
			self.union = self.chrono.fetcher.fetchUnion();
		});

		self.$scope.$on('notification', function(event, n) {
			self.$log.debug('[PlayerController] on notification');

			event.stopPropagation();

			self.events.push('Notification pushed');
			self.notifications.push(n);
		});

		self.$scope.$on('settings', function(event) {
			self.$log.debug('[PlayerController] on player_settings');

			event.stopPropagation();

			self.events.push('Settings updated');
			self.settings = self.chrono.fetcher.fetchSettings();
		});
	};

	PlayerController.prototype.autoApply = function() {
		this.$log.debug('[PlayerController] autoApply');

		var self = this;
		self.interval = self.$interval(function() {}, 1000);
		self.$scope.$on('$destroy', function() {
			self.$interval.cancel(self.interval);
		});
	};

	PlayerController.prototype.socketOffline = function() {
		this.$log.debug('[PlayerController] socketOffline');

		this.chrono.online.offlineAsync();
	};

	angular
		.module('Chrono.Demo')
		.controller('Chrono.Demo.PlayerController', [
			'Chrono.Api', '$interval', '$log', '$scope', '$window',
			PlayerController
		]);

})(angular);
