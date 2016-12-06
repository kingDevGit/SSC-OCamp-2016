<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SocketIO\Emitter;

class SocketIOEmitterServiceProvider extends ServiceProvider {
	protected $defer = true;

	public function register() {

	}

	public function boot() {
		$tinyRedisClient = $this->app->make(\TinyRedisClient::class);
		$this->app->singleton(Emitter::class, function () use ($tinyRedisClient) {
			return new Emitter($tinyRedisClient);
		});
	}

	public function provides() {
		return [Emitter::class];
	}
}
