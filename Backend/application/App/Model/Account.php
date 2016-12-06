<?php namespace App\Model;

use App\Exceptions\ValidationException;
use App\Model\Base\Account as BaseAccount;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Map\TableMap;

class Account extends BaseAccount {
	public function preSave(ConnectionInterface $con = null) {
		if (!$this->validate()) {
			throw new ValidationException();
		}

		return true;
	}

	public function toAssoc() {
		$result = $this->toArray(TableMap::TYPE_FIELDNAME);
		unset($result['hash']);
		return $result;
	}

	public function setPassword($password) {
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$this->setHash($hash);
	}

	public function verifyPassword($password) {
		return password_verify($password, $this->getHash());
	}
}
