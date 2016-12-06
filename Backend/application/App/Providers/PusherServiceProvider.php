<?php namespace App\Providers;

use App\Pusher;
use Illuminate\Support\ServiceProvider;
use SocketIO\Emitter;

class PusherServiceProvider extends ServiceProvider {
	protected $defer = true;

	public function register() {

	}

	public function boot() {
		$emitter = $this->app->make(Emitter::class);
		$this->app->singleton(Pusher::class, function () use ($emitter) {
			return new Pusher($emitter);
		});
	}

	public function provides() {
		return [Pusher::class];
	}
}
