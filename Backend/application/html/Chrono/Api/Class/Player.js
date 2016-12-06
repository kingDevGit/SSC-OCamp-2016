(function(angular) {
	'use strict';

	function Player() {
		this.id = null;
		this.nickname = null;
		this.gender = null;
		this.union_id = null;
		this.tags = [];
		this.address = null;
		this.die_count = null;
		this.created_at = null;
		this.updated_at = null;

		this.union = null;
		this.timers = [];
	}

	Player.prototype.hydrate = function(obj) {
		obj = obj || {};

		var id = (obj.id !== undefined) ? obj.id : null;
		this.id = (id !== null) ? parseInt(id) : null;

		var nickname = (obj.nickname !== undefined) ? obj.nickname : null;
		this.nickname = nickname !== null ? '' + nickname : null;

		var gender = (obj.gender !== undefined) ? obj.gender : null;
		this.gender = gender !== null ? '' + gender : null;

		var union_id = (obj.union_id !== undefined) ? obj.union_id : null;
		this.union_id = union_id !== null ? parseInt(union_id) : null;

		var tags = (obj.tags !== undefined) ? obj.tags : null;
		this.tags = tags !== null ? Array.prototype.slice.call(tags) : [];

		var address = (obj.address !== undefined) ? obj.address : null;
		this.address = address !== null ? '' + address : null;

		var die_count = (obj.die_count !== undefined) ? obj.die_count : null;
		this.die_count = die_count !== null ? parseInt(die_count) : null;

		var created_at = (obj.created_at !== undefined) ? obj.created_at : null;
		this.created_at = created_at !== null ? new Date(created_at) : null;

		var updated_at = (obj.updated_at !== undefined) ? obj.updated_at : null;
		this.updated_at = updated_at !== null ? new Date(updated_at) : null;

		var union = (obj.union !== undefined) ? obj.union : null;
		if (union !== null) {
			this.union = new Player.Union();
			this.union.hydrate(union);
		}

		var timers = (obj.timers !== undefined) ? obj.timers : null;
		if (timers !== null) {
			timers = Array.prototype.slice.call(timers);
			this.timers = timers.map(function(obj) {
				var timer = obj.class_key && obj.class_key === 'bomb'
					? new Player.Bomb()
					: new Player.Timer();

				timer.hydrate(obj);

				return timer;
			});
		}
	};

	Player.prototype.getPrimaryTimer = function() {
		var timers = this.timers;
		if (timers.length < 1) return null;

		for (var i = 0; i < timers.length; i++) {
			if (timers[i] instanceof Player.Bomb) {
				return timers[i];
			}
		}

		return timers[0];
	};

	Player.prototype.getSecondaryTimer = function() {
		var timers = this.timers;
		if (timers.length < 2) return null;

		for (var i = 0; i < timers.length; i++) {
			if (timers[i] instanceof Player.Timer) {
				return timers[i];
			}
		}

		return timers[0];

	};

	Player.prototype.getRemainSecond = function() {
		var timer = this.getPrimaryTimer();
		return timer ? timer.getRemainSecond() : null;
	};

	Player.prototype.setRemainSecond = function(second) {
		var timer = this.getPrimaryTimer();

		if (timer) {
			timer.setRemainSecond(second);
		} else {
			throw new Player.TimerNotFoundException();
		}
	};

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Class.Player', [
			'Chrono.Api.Class.Bomb',
			'Chrono.Api.Class.Timer',
			'Chrono.Api.Class.Union',
			'Chrono.Api.Exceptions.TimerNotFoundException',
			function(Bomb, Timer, Union, TimerNotFoundException) {
				Player.Bomb = Bomb;
				Player.Timer = Timer;
				Player.Union = Union;
				Player.TimerNotFoundException = TimerNotFoundException;
				return Player;
			}
		]);

})(angular);
