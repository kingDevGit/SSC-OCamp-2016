<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RedisServiceProvider extends ServiceProvider {
	protected $defer = true;

	public function register() {
		$this->app->singleton(\TinyRedisClient::class, function () {
			return new \TinyRedisClient('redis:6379');
		});
	}

	public function provides() {
		return [\TinyRedisClient::class];
	}
}
