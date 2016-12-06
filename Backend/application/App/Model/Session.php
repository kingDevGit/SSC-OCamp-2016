<?php namespace App\Model;

use Propel\Runtime\Map\TableMap;
use App\Model\Base\Session as BaseSession;
use Propel\Runtime\Util\PropelDateTime;

class Session extends BaseSession {
	public function toAssoc() {
		return $this->toArray(TableMap::TYPE_FIELDNAME);
	}

	private function randomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function generateToken() {
		$this->setToken($this->randomString(80));
	}

	public function deferExpiredAt() {
		$datetime = PropelDateTime::newInstance('now');
		$datetime->add(new \DateInterval('P30D'));
		$this->setExpiredAt($datetime);
	}
}
