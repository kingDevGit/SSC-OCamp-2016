angular.module('starter.controllers', [])

	.controller('AppCtrl', ['Chrono.Api', '$log', '$scope', '$window', 'UserService', '$state', '$timeout', '$interval', '$log', '$ionicPopup', '$http', '$ionicPopover', '$ionicLoading', '$ionicModal', function(api, $log, $scope, $window, UserService, $state, $timeout, $interval, $log, $ionicPopup, $http, $ionicPopover, $ionicLoading, $ionicModal) {
		$scope.game = {
			isLoaded: false,
			isPaused: false,
			player: null,
			events: [],
			primaryTimer: null,
			secondaryTimer: null,
			union: null,
			notifications: [],
			settings: null
		}
		$scope.newMsg = false;
		$scope.interval = null;
		$scope.trans = {
			id: null,
			time: null
		}
		$scope.rendered = false;
		$scope.countDown = 1000;
		$scope.token = null;
		$scope.tickInterval = 1000;
		$scope.host = "http://chrono.kenny-tang.com/api";
		$scope.disconnected = false;
		$scope.firstIn = 0;

		$scope.interval = $interval(function() {
			$scope.date = moment().format('MMMM Do YYYY h:mm:ss ');
			if ($scope.game.isLoaded) {
				$scope.resetTimer();
			}

		}, 1000);

		$scope.$on('$ionicView.loaded', function() {
			$scope.init();
		})

		$scope.timeSince = function(date) {
			var seconds = Math.floor((new Date() - date) / 1000);
			var interval = Math.floor(seconds / 31536000);
			if (interval >= 1) {
				return interval + " 年前";
			}
			interval = Math.floor(seconds / 2592000);
			if (interval >= 1) {
				return interval + " 個月前";
			}
			interval = Math.floor(seconds / 86400);
			if (interval >= 1) {
				return interval + " 日前";
			}
			interval = Math.floor(seconds / 3600);
			if (interval >= 1) {
				return interval + " 小時前";
			}
			interval = Math.floor(seconds / 60);
			if (interval >= 1) {
				return interval + " 分鐘前";
			}
			return Math.floor(seconds) + " 秒前";
		}

		$scope.init = function() {

			if ($scope.firstIn == 0) {
				$ionicLoading.show({
					template: '載入中...'
				});
			}
			$log.debug("[Game Page] Init ");

			var self = this;
			api.auth
				.verifyAsync(UserService.getUser())
				.then(function() {

					$log.debug('[PlayerController.init] Auth OK');

					api.online.setScope($scope.$new());
					return api.online.onlineAsync();
				})
				.then(function() {
					$log.debug('[PlayerController.init] OK 2');

					$scope.game.events.push('Online success');

					$scope.game.player = api.fetcher.fetchPlayer();
					$scope.game.primaryTimer = $scope.game.player.getPrimaryTimer();
					$scope.game.secondaryTimer = $scope.game.player.getSecondaryTimer();
					$scope.game.union = api.fetcher.fetchUnion();
					$scope.game.notifications = api.fetcher.fetchNotifications();
					$scope.game.settings = api.fetcher.fetchSettings();
					$scope.countDown = $scope.game.primaryTimer.getRemainSecond();
					$log.debug($scope.game.player.nickname + " Login");
					if ($scope.firstIn == 0) {
						$log.debug("First In Login");
						$scope.handleEvent();
						//$scope.autoApply();
						$scope.firstIn = 1;
					}
					$scope.game.isLoaded = true;
					$ionicLoading.hide();

				}).catch(function(exception) {
				$ionicLoading.hide();
				api.online.offlineAsync();
				api.auth.logoutAsync()
				$log.debug('[PlayerController.init] Error');
				UserService.logoutUser();
				$state.go('app.login');

			});
		}

		$scope.handleEvent = function() {
			$log.debug('[PlayerController] handleEvent');

			$scope.$on('disconnect', function() {
				$log.debug('[PlayerController] on disconnect');
				$scope.game.events.push('Disconnected');
				$scope.disconnected = true;
				$scope.$broadcast('timer-stop');
			});

			$scope.$on('reconnect', function() {
				$log.debug('[PlayerController] on reconnect');
				$scope.game.events.push('Reconnect success');
				$scope.disconnected = false;
				$scope.init();
			});

			$scope.$on('reconnect_attempt', function() {
				$log.debug('[PlayerController] on reconnect_attempt');
				$scope.game.events.push('Reconnecting...');
			});

			$scope.$on('reconnect_error', function() {
				$log.debug('[PlayerController] on reconnect_error');
				$scope.game.events.push('Reconnect error');
			});

			$scope.$on('reconnect_failed', function() {
				$log.debug('[PlayerController] on reconnect_failed');
				$scope.game.events.push('Reconnect failed');
			});

			$scope.$on('player', function(event) {
				$log.debug('[PlayerController] on player');

				event.stopPropagation();

				$scope.game.events.push('Player updated');
				$scope.game.player = api.fetcher.fetchPlayer();
				$scope.game.primaryTimer = $scope.game.player.getPrimaryTimer();
				$scope.game.secondaryTimer = $scope.game.player.getSecondaryTimer();
				$scope.resetTimer();
			});

			$scope.$on('union', function(event) {
				$log.debug('[PlayerController] on union');

				event.stopPropagation();

				$scope.game.events.push('Union updated');
				$scope.game.union = api.fetcher.fetchUnion();
			});

			$scope.$on('notification', function(event, n) {
				$log.debug('[PlayerController] on notification');

				event.stopPropagation();

				$scope.game.events.push('Notification pushed');
				$scope.game.notifications.push(n);
				$scope.newMsg = true;

			});

			$scope.$on('settings', function(event) {
				$log.debug('[PlayerController] on player_settings');

				event.stopPropagation();

				$scope.game.events.push('Settings updated');
				$scope.game.settings = api.fetcher.fetchSettings();
			});
		};

		// $scope.autoApply = function() {
		// 	$log.debug('[PlayerController] autoApply');

		// 	$scope.interval = $interval(function() {}, 1000);
		// 	$scope.$on('$destroy', function() {
		// 		$interval.cancel($scope.interval);
		// 	});

		// };

		$scope.resetTimer = function() {

			if ($scope.disconnected == false) {
				$scope.$broadcast('timer-set-countdown', $scope.game.primaryTimer.getRemainSecond());
				if ($scope.game.primaryTimer.pause != true) $scope.$broadcast('timer-start');
				$log.debug('Timer reset [OK]');
			}
		}

		$scope.isTransactionAllow = function() {
			return api.transaction.isTransactionAllow();

		}

		$scope.isAwardAllow = function() {
			return api.transaction.isAwardAllow();

		}

		$scope.isKillAllow = function() {
			return api.transaction.isKillAllow();

		}

		$scope.createTransactionPopup = function() {
			$scope.trans = {};

			$ionicPopup.show({
				template: '<input type="text" ng-model="trans.id" placeholder="裝置 ID"> <br ><input type="text" placeholder="時間 (分鐘)" ng-model="trans.time">',
				title: '輸入傳送目標 ID 和時間 ',
				subTitle: '時間以分鐘計算',
				scope: $scope,
				buttons: [
					{ text: '取消' },
					{
						text: '<b>傳送</b>',
						type: 'button-dark',
						onTap: function(e) {
							if ($scope.game.player && $scope.game.player.union && $scope.game.player.union.name && $scope.game.player.union.name === $scope.trans.id) {
								$scope.blueCardModal = $ionicModal.fromTemplate(
									'<ion-modal-view><ion-header-bar class="bar-dark"><h1 class="title">通行晶片</h1><button class="button button-clear button-light" ng-click="blueCardModal.remove()">完成</button></ion-header-bar><ion-content class="blueCardPage"></ion-content></ion-modal-view>',
									{ scope: $scope }
								);

								$scope.blueCardModal.show();
							} else {
								if (!$scope.trans.id || !$scope.trans.time) {
									e.preventDefault();
								} else {
									api.transaction.createAsync($scope.trans.id, parseInt($scope.trans.time, 10) * 60).catch(function(error) {
										$log.debug(error);
										$ionicPopup.alert({ title: 'Error' });
									});

								}
							}
						}
					}
				]
			});
		};

		$scope.awardPlayerPopup = function() {
			$scope.trans = {};

			$ionicPopup.show({
				template: '<input type="text" ng-model="trans.id" placeholder="裝置 ID"> <br ><input type="text" placeholder="時間 (分鐘)" ng-model="trans.time">',
				title: '輸入調整目標 ID 和時間 ',
				subTitle: '時間以分鐘計算',
				scope: $scope,
				buttons: [
					{ text: '取消' },
					{
						text: '<b>傳送</b>',
						type: 'button-dark',
						onTap: function(e) {
							if (!$scope.trans.id || !$scope.trans.time) {
								e.preventDefault();
							} else {
								api.transaction.awardPlayerAsync($scope.trans.id, parseInt($scope.trans.time, 10) * 60).catch(function(error) {
									$log.debug(error);
									$ionicPopup.alert({ title: 'Error' });
								});
							}
						}
					}
				]
			});
		};

		$scope.awardUnionPopup = function() {
			$scope.trans = {};

			$ionicPopup.show({
				template: '<input type="text" ng-model="trans.id" placeholder="小組 ID"> <br ><input type="text" placeholder="時間 (分鐘)" ng-model="trans.time">',
				title: '小組 ID: [長2] [洲3] [賓4] [客5] [人6] [數7]',
				subTitle: '時間以分鐘計算（每人）',
				scope: $scope,
				buttons: [
					{ text: '取消' },
					{
						text: '<b>傳送</b>',
						type: 'button-dark',
						onTap: function(e) {
							if (!$scope.trans.id || !$scope.trans.time) {
								e.preventDefault();
							} else {
								api.transaction.awardUnionAsync($scope.trans.id, parseInt($scope.trans.time, 10) * 60).catch(function(error) {
									$log.debug(error);
									$ionicPopup.alert({ title: 'Error' });
								});
							}
						}
					}
				]
			});
		};

		$scope.killPopup = function() {
			$scope.trans = {};

			$ionicPopup.show({
				template: '<input type="text" ng-model="trans.id" placeholder="裝置 ID">',
				title: '輸入殺害目標',
				scope: $scope,
				buttons: [
					{ text: '取消' },
					{
						text: '<b>傳送</b>',
						type: 'button-dark',
						onTap: function(e) {
							if (!$scope.trans.id) {
								e.preventDefault();
							} else {
								api.transaction.killAsync($scope.trans.id).catch(function(error) {
									$log.debug(error);
									$ionicPopup.alert({ title: 'Error' });
								});
							}
						}
					}
				]
			});
		};

		$scope.msgBox = function($event) {
			$scope.popover.show($event).then(function() {
				$scope.newMsg = false;
			});
		};
		$scope.eventBox = function($event) {
			$scope.popover2.show($event).then(function() {

			});
		};

		$ionicPopover.fromTemplateUrl('templates/notification.html', {
			scope: $scope
		}).then(function(popover) {
			$scope.popover = popover;
		});

		$ionicPopover.fromTemplateUrl('templates/event.html', {
			scope: $scope
		}).then(function(popover) {
			$scope.popover2 = popover;
		});

		$scope.isDie = function() {
			if ($scope.game.primaryTimer.getRemainSecond() < 1) {
				return true;
			} else return false;
		}

		$scope.started = function() {
			if ($scope.game.settings.game_status == "before_event") {

				return false;
			} else return true;

		}

		$scope.logout = function() {
			$log.debug('[PlayerController] socketOffline');
			$interval.cancel($scope.interval);
			UserService.logoutUser();
			api.online.offlineAsync();
			api.auth.logoutAsync()
			$log.debug('[LoginController.logout]');
			$scope.token = "";
			$scope.popover.remove();
			$state.go('app.login');

		};

	}])

	.controller('LoginCtrl', ['Chrono.Api', '$log', '$scope', '$window', 'UserService', '$state', '$log', '$ionicLoading', '$ionicPopup', function(api, $log, $scope, $window, UserService, $state, $log, $ionicLoading, $ionicPopup) {

		$scope.$on('$ionicView.loaded', function() {

			$scope.init();
		})

		$scope.init = function() {
			$log.debug('[INIT] OK');
			$scope.tokenVerified = false;
			$scope.token = UserService.getUser();
			$scope.isError = false;
			$scope.loginFields = {
				email: null,
				pw: null
			};

			$scope.loginByToken();
		}

		$scope.loginByEmail = function() {
			$ionicLoading.show({
				template: 'Login To The System...'
			});

			$log.debug('LoginByEmail');

			if (!$scope.loginFields.email || !$scope.loginFields.pw) {
				return $window.alert('Email & Password cannot be empty!');
			}
			var self = this;
			api.auth.loginAsync($scope.loginFields.email, $scope.loginFields.pw)
				.then(function() {
					$ionicLoading.hide();
					$log.debug('[LoginController.loginByEmailPassword] OK');
					$scope.tokenVerified = true;
					$scope.token = api.auth.getToken();
					UserService.setUser($scope.token);
					$state.go('app.browse');
					self.exception = '';
				})
				.catch(function(exception) {
					$ionicLoading.hide();
					$log.debug('[LoginController.loginByEmailPassword] Error', exception);
					$scope.tokenVerified = false;
					$scope.token = '';
					$window.alert('Error Occured!');
					if (exception instanceof api.exceptions.EmailPasswordNotMatchException) {
						self.exception = 'EmailPasswordNotMatchException';
					} else if (exception instanceof api.exceptions.ConnectionError) {
						self.exception = 'ConnectionError';
					} else {
						self.exception = 'UnknownError (See console)';
					}
				});
		};

		$scope.loginByToken = function() {
			$log.debug('[LoginController.loginByToken]');

			if (!$scope.token) {
				return $log.debug('Token Login Fail');
			}

			var self = this;
			api.auth.verifyAsync($scope.token)
				.then(function() {
					$log.debug('[LoginController.loginByToken] OK');
					$scope.tokenVerified = true;
					$scope.token = api.auth.getToken();
					UserService.setUser($scope.token);
					$state.go('app.browse');
					self.exception = '';
				})
				.catch(function(exception) {
					$log.debug('[LoginController.loginByToken] Error', exception);
					$scope.tokenVerified = false;
					$scope.token = '';
					if (exception instanceof self.chrono.exceptions.TokenNotValidException) {
						self.exception = 'TokenNotValidException';
					} else if (exception instanceof self.chrono.exceptions.ConnectionError) {
						self.exception = 'ConnectionError';
					} else {
						self.exception = 'UnknownError (See console)';
					}
				});
		};

		$scope.showLogin = function() {
			$scope.data = {};

			// An elaborate, custom popup
			var myPopup = $ionicPopup.show({
				template: '<input type="text" placeholder="通行編號" ng-model="loginFields.email"><br><input type="password" placeholder="密碼"ng-model="loginFields.pw">',
				title: '請輸入您的通行號碼與密碼',
				subTitle: 'Chrono Science 歡迎您',
				scope: $scope,
				buttons: [
					{ text: '取消' },
					{
						text: '<b>登入</b>',
						type: 'button-positive',
						onTap: function(e) {
							$scope.loginByEmail();
						}
					}
				]
			});

		};

	}])

	.controller('MenuCtrl', function($scope, $stateParams) {
	});
