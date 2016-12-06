<?php

const PREFIX = '/api';

$app->get(PREFIX . '/score-board', ['uses' => 'Controller@scoreBoard']);
$app->post(PREFIX . '/auth/login', ['uses' => 'AuthController@login']);

$app->group(
	[
		'prefix' => PREFIX,
		'namespace' => 'App\Http\Controllers',
		'middleware' => [
			App\Http\Middleware\AuthMiddleware::KEY,
		],
	],
	function () use ($app) {
		$app->get('/auth/verify', ['uses' => 'AuthController@verify']);
		$app->post('/auth/logout', ['uses' => 'AuthController@logout']);
		$app->post('/auth/password', ['uses' => 'AuthController@changePassword']);

		$app->post('/online', ['uses' => 'Controller@online']);
		$app->post('/offline', ['uses' => 'Controller@offline']);
		$app->get('/settings', ['uses' => 'Controller@getSettings']);

		$app->get('/me', ['uses' => 'PlayerController@getMe']);
		$app->get('/me/notifications', ['uses' => 'PlayerController@getNotifications']);

		$app->post('/transaction/create', ['uses' => 'TransactionController@create']);
		$app->post('/transaction/award-player', ['uses' => 'TransactionController@awardPlayer']);
		$app->post('/transaction/award-union', ['uses' => 'TransactionController@awardUnion']);
		$app->post('/transaction/kill', ['uses' => 'TransactionController@kill']);
	}
);

$app->group(
	[
		'prefix' => PREFIX . '/admin',
		'namespace' => 'App\Http\Controllers\Admin',
		'middleware' => [
			App\Http\Middleware\AuthMiddleware::KEY,
			App\Http\Middleware\AdminMiddleware::KEY,
		],
	],
	function () use ($app) {
		$app->post('/settings', ['uses' => 'SettingsController@setSettings']);
	}
);
