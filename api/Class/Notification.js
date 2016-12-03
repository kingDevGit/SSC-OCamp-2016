(function(angular) {
	'use strict';

	function Notification() {
		this.id = null;
		this.to_player = null;
		this.message = null;
		this.created_at = null;
		this.updated_at = null;
	}

	Notification.prototype.hydrate = function(obj) {
		obj = obj || {};

		var id = (obj.id !== undefined) ? obj.id : null;
		this.id = (id !== null) ? parseInt(id) : null;

		var to_player = (obj.to_player !== undefined) ? obj.to_player : null;
		this.to_player = (to_player !== null) ? parseInt(to_player) : null;

		var message = (obj.message !== undefined) ? obj.message : null;
		this.message = message !== null ? '' + message : null;

		var created_at = (obj.created_at !== undefined) ? obj.created_at : null;
		this.created_at = created_at !== null ? new Date(created_at) : null;

		var updated_at = (obj.updated_at !== undefined) ? obj.updated_at : null;
		this.updated_at = updated_at !== null ? new Date(updated_at) : null;
	};

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Class.Notification', [function() { return Notification }]);

})(angular);
