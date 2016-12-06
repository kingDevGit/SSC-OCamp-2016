<?php

return [
	'propel' => [
		'database' => [
			'connections' => [
				'chrono' => [
					'adapter' => 'mysql',
					'classname' => 'Propel\Runtime\Connection\ConnectionWrapper',
					'dsn' => 'mysql:host=db;port=3306;dbname=%env.MYSQL_DATABASE%;charset=utf8mb4',
					'user' => 'root',
					'password' => '%env.MYSQL_ROOT_PASSWORD%',
					'attributes' => [],
				]
			]
		],
		'runtime' => [
			'defaultConnection' => 'chrono',
			'connections' => ['chrono']
		],
		'generator' => [
			'defaultConnection' => 'chrono',
			'connections' => ['chrono']
		],
		'paths' => [
			'phpDir' => '.',
			'schemaDir' => 'database/schema',
			'phpConfDir' => 'database/config',
			'migrationDir' => 'database/migration'
		]
	]
];
