(function(angular) {
	'use strict';

	function Bomb() {
		Bomb._super.call(this);
	}

	Bomb.prototype.getClass = function() {
		return Bomb;
	};

	angular
		.module('Chrono.Api')
		.factory('Chrono.Api.Class.Bomb', [
			'Chrono.Api.Class.Timer',
			function(Timer) {
				var __extends = function(d, b) {
					for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
					function __() { this.constructor = d; }

					d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
				};

				Bomb._super = Timer;
				__extends(Bomb, Bomb._super);

				return Bomb;
			}
		]);

})(angular);
