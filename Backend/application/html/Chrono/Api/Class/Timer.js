(function(angular) {
	'use strict';

	function Timer() {
		this.id = null;
		this.player_id = null;
		this.pause = null;
		this.pause_at = null;
		this.end_at = null;
		this.created_at = null;
		this.updated_at = null;
	}

	Timer.prototype.hydrate = function(obj) {
		obj = obj || {};

		var id = (obj.id !== undefined) ? obj.id : null;
		this.id = (id !== null) ? parseInt(id) : null;

		var player_id = (obj.player_id !== undefined) ? obj.player_id : null;
		this.player_id = player_id !== null ? parseInt(player_id) : null;

		var pause = (obj.pause !== undefined) ? obj.pause : null;
		this.pause = pause !== null ? !!pause : null;

		var pause_at = (obj.pause_at !== undefined) ? obj.pause_at : null;
		this.pause_at = pause_at !== null ? new Date(pause_at) : null;

		var end_at = (obj.end_at !== undefined) ? obj.end_at : null;
		this.end_at = end_at !== null ? new Date(end_at) : null;

		var created_at = (obj.created_at !== undefined) ? obj.created_at : null;
		this.created_at = created_at !== null ? new Date(created_at) : null;

		var updated_at = (obj.updated_at !== undefined) ? obj.updated_at : null;
		this.updated_at = updated_at !== null ? new Date(updated_at) : null;
	};

	Timer.prototype.getRemainSecond = function() {
		var startAt = this.pause
			? this.pause_at
			: new Date();

		var second = Math.floor((this.end_at.getTime() - startAt.getTime()) / 1000);
		return second;
	};

	Timer.prototype.setRemainSecond = function(second) {
		var startAt = this.pause
			? this.pause_at
			: new Date();

		this.end_at = new Date(startAt.getTime() + (second * 1000));
	};

	Timer.prototype.isTimeUp = function() {
		var now = new Date();
		return !this.pause && this.end_at.getTime() < now.getTime();
	};

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Class.Timer', [function() { return Timer }]);

})(angular);
