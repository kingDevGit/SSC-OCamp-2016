(function(angular) {
	'use strict';

	function Union() {
		this.id = null;
		this.name = null;
		this.color = null;
		this.created_at = null;
		this.updated_at = null;
	}

	Union.prototype.hydrate = function(obj) {
		obj = obj || {};

		var id = (obj.id !== undefined) ? obj.id : null;
		this.id = (id !== null) ? parseInt(id) : null;

		var name = (obj.name !== undefined) ? obj.name : null;
		this.name = name !== null ? '' + name : null;

		var color = (obj.color !== undefined) ? obj.color : null;
		this.color = color !== null ? '' + color : null;

		var created_at = (obj.created_at !== undefined) ? obj.created_at : null;
		this.created_at = created_at !== null ? new Date(created_at) : null;

		var updated_at = (obj.updated_at !== undefined) ? obj.updated_at : null;
		this.updated_at = updated_at !== null ? new Date(updated_at) : null;
	};

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Class.Union', [function() { return Union }]);

})(angular);
