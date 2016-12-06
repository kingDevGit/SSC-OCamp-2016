<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('chrono', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
  'dsn' => 'mysql:host=db;port=3306;dbname=chrono;charset=utf8mb4',
  'user' => 'root',
  'password' => 'drEEM3h8dHvsprexXrt2SAzw',
  'attributes' =>
  array (
    'ATTR_EMULATE_PREPARES' => false,
    'ATTR_TIMEOUT' => 30,
  ),
  'model_paths' =>
  array (
    0 => 'src',
    1 => 'vendor',
  ),
));
$manager->setName('chrono');
$serviceContainer->setConnectionManager('chrono', $manager);
$serviceContainer->setDefaultDatasource('chrono');