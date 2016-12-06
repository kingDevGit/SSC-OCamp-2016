<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../database/config/config.php';

try {
	(new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {}

$app = new Laravel\Lumen\Application(realpath(__DIR__ . '/../'));

$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, App\Exceptions\Handler::class);
$app->singleton(Illuminate\Contracts\Console\Kernel::class, App\Console\Kernel::class);

$app->register(App\Providers\RedisServiceProvider::class);
$app->register(App\Providers\SocketIOEmitterServiceProvider::class);
$app->register(App\Providers\PusherServiceProvider::class);

$app->routeMiddleware([
	App\Http\Middleware\AuthMiddleware::KEY => App\Http\Middleware\AuthMiddleware::class,
	App\Http\Middleware\AdminMiddleware::KEY => App\Http\Middleware\AdminMiddleware::class
]);

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
	require __DIR__ . '/../App/Http/routes.php';
});

return $app;
