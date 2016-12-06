<?php namespace App\Model;

use App\Exceptions\PlayerNotFoundException;
use App\Exceptions\TimerNotFoundException;
use App\Model\Base\Transaction as BaseTransaction;
use Propel\Runtime\Connection\ConnectionInterface;

class Transaction extends BaseTransaction {
	public function preInsert(ConnectionInterface $con = null) {
		if ($this->getPlayerA() === $this->getPlayerB()) {
			throw new PlayerNotFoundException();
		}

		if ($this->getPlayerRelatedByPlayerA() === null || $this->getPlayerRelatedByPlayerB() === null) {
			throw new PlayerNotFoundException();
		}

		if ($this->getPlayerRelatedByPlayerA()->getPrimaryTimer() === null || $this->getPlayerRelatedByPlayerB()->getPrimaryTimer() === null) {
			throw new TimerNotFoundException();
		}

		return true;
	}

	public function execute(ConnectionInterface $con = null) {
		$this->getPlayerRelatedByPlayerA()->setRemainSecond(
			$this->getPlayerRelatedByPlayerA()->getRemainSecond() - $this->getSecond()
		);

		$this->getPlayerRelatedByPlayerB()->setRemainSecond(
			$this->getPlayerRelatedByPlayerB()->getRemainSecond() + $this->getSecond()
		);

		$this->getPlayerRelatedByPlayerA()->save($con);
		$this->getPlayerRelatedByPlayerB()->save($con);

		$this->setExecuted(true);
		$this->save($con);
	}
}
