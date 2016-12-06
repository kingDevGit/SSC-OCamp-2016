<?php namespace App\Model;

use App\Model\Base\NotificationQuery as BaseNotificationQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;

class NotificationQuery extends BaseNotificationQuery {
	public function preSelect(ConnectionInterface $con) {
	}
}
