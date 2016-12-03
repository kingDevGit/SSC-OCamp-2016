(function(angular) {
	'use strict';

	function ChronoApiTransaction(auth, fetcher, host, TokenNotValidException, ViolateSettingsException, $http, $log, $q) {
		this.auth = auth;
		this.fetcher = fetcher;
		this.host = host;

		this.TokenNotValidException = TokenNotValidException;
		this.ViolateSettingsException = ViolateSettingsException;

		this.$http = $http;
		this.$log = $log;
		this.$q = $q;

		this.$log.debug('[ChroChronoApiTransactionnoApiOnline] construct');

		this.$scope = null;
		this.sid = null;
		this.admin = false;
	}

	ChronoApiTransaction.prototype.isTransactionAllow = function() {
		var player = this.fetcher.fetchPlayer();
		var settings = this.fetcher.fetchSettings();

		return settings['allow_transaction'] === 'yes' || player.tags.indexOf('oc') > -1
	};

	ChronoApiTransaction.prototype.isAwardAllow = function() {
		var player = this.fetcher.fetchPlayer();

		return player.tags.indexOf('oc') > -1
	};

	ChronoApiTransaction.prototype.isKillAllow = function() {
		var player = this.fetcher.fetchPlayer();

		return player.tags.indexOf('oc') > -1
	};

	ChronoApiTransaction.prototype.createAsync = function(address, second) {
		this.$log.debug('[ChronoApiTransaction.createAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		if (!self.isTransactionAllow()) {
			return this.$q.reject(new self.ViolateSettingsException());
		}

		return self.$http
			.post(self.host + '/transaction/create', {
				'address': address,
				'second': second
			}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiTransaction.createAsync] OK');
				return self.$q.resolve();
			});
	};

	ChronoApiTransaction.prototype.awardPlayerAsync = function(address, second) {
		this.$log.debug('[ChronoApiTransaction.awardPlayerAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		if (!self.isAwardAllow()) {
			return this.$q.reject(new self.ViolateSettingsException());
		}

		return self.$http
			.post(self.host + '/transaction/award-player', {
				'address': address,
				'second': second
			}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiTransaction.awardPlayerAsync] OK');
				return self.$q.resolve();
			});
	};

	ChronoApiTransaction.prototype.awardUnionAsync = function(union, second) {
		this.$log.debug('[ChronoApiTransaction.awardUnionAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		if (!self.isAwardAllow()) {
			return this.$q.reject(new self.ViolateSettingsException());
		}

		return self.$http
			.post(self.host + '/transaction/award-union', {
				'union': union,
				'second': second
			}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiTransaction.awardUnionAsync] OK');
				return self.$q.resolve();
			});
	};

	ChronoApiTransaction.prototype.killAsync = function(address) {
		this.$log.debug('[ChronoApiTransaction.killAsync]');

		var self = this;
		var token = self.auth.getToken();

		if (token === null) {
			return this.$q.reject(new self.TokenNotValidException());
		}

		if (!self.isKillAllow()) {
			return this.$q.reject(new self.ViolateSettingsException());
		}

		return self.$http
			.post(self.host + '/transaction/kill', {
				'address': address
			}, {
				headers: { 'Chrono-Token': token }
			})
			.then(function() {
				self.$log.debug('[ChronoApiTransaction.killAsync] OK');
				return self.$q.resolve();
			});
	};

	angular
		.module('Chrono.Api')
		.service('Chrono.Api.Transaction', [
			'Chrono.Api.Auth',
			'Chrono.Api.Fetcher',
			'Chrono.Api.Constant.host',
			'Chrono.Api.Exceptions.TokenNotValidException',
			'Chrono.Api.Exceptions.ViolateSettingsException',
			'$http',
			'$log',
			'$q',
			ChronoApiTransaction
		]);

})(angular);
