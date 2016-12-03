(function(angular) {
	'use strict';

	function ChronoApi(auth, fetcher, online, socket, transaction, Bomb, Notification, Player, Timer, Union, ConnectionError, EmailPasswordNotMatchException, TimerNotFoundException, TokenNotValidException, UnknownError, ViolateSettingsException, $log) {
		this.auth = auth;
		this.fetcher = fetcher;
		this.online = online;
		this.socket = socket;
		this.transaction = transaction;

		this.class = {
			Bomb: Bomb,
			Notification: Notification,
			Player: Player,
			Timer: Timer,
			Union: Union
		};

		this.exceptions = {
			ConnectionError: ConnectionError,
			EmailPasswordNotMatchException: EmailPasswordNotMatchException,
			TimerNotFoundException: TimerNotFoundException,
			TokenNotValidException: TokenNotValidException,
			UnknownError: UnknownError,
			ViolateSettingsException: ViolateSettingsException
		};

		this.$log = $log;

		this.$log.debug('[Chrono.Api] construct');
	}

	angular
		.module('Chrono.Api', [])
		.config(['$httpProvider', function($httpProvider) {
			$httpProvider.defaults.cache = false;
			$httpProvider.defaults.headers = $httpProvider.defaults.headers || {};
			$httpProvider.defaults.headers.common['Content-Type'] = 'application/json';
			$httpProvider.interceptors.push('Chrono.Api.Exceptions.Handler');
		}])
		.constant('Chrono.Api.Constant.host', 'http://chrono.kenny-tang.com/api')
		.constant('Chrono.Api.Constant.socketHost', 'http://chrono.kenny-tang.com:3000')
		.service('Chrono.Api', [
			'Chrono.Api.Auth',
			'Chrono.Api.Fetcher',
			'Chrono.Api.Online',
			'Chrono.Api.Socket',
			'Chrono.Api.Transaction',
			'Chrono.Api.Class.Bomb',
			'Chrono.Api.Class.Notification',
			'Chrono.Api.Class.Player',
			'Chrono.Api.Class.Timer',
			'Chrono.Api.Class.Union',
			'Chrono.Api.Exceptions.ConnectionError',
			'Chrono.Api.Exceptions.EmailPasswordNotMatchException',
			'Chrono.Api.Exceptions.TimerNotFoundException',
			'Chrono.Api.Exceptions.TokenNotValidException',
			'Chrono.Api.Exceptions.UnknownError',
			'Chrono.Api.Exceptions.ViolateSettingsException',
			'$log',
			ChronoApi
		]);

})(angular);
