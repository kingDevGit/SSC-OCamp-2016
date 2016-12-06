<?php namespace App\Model;

use App\Model\Base\Timer as BaseTimer;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Util\PropelDateTime;

class Timer extends BaseTimer {
	public function toAssoc() {
		return $this->toArray(TableMap::TYPE_FIELDNAME);
	}

	public function start() {
		if ($this->isPause()) {
			$this->setPause(false);

			$interval = date_diff($this->getPauseAt(), $this->getEndAt());

			$this->setPauseAt(null);
			$this->setEndAt(PropelDateTime::newInstance('now')->add($interval));
		}
	}

	public function pause() {
		if (!$this->isPause()) {
			$this->setPause(true);
			$this->setPauseAt(PropelDateTime::newInstance('now'));
		}
	}

	public function getRemainSecond() {
		$startAt = $this->isPause()
			? $this->getPauseAt()
			: PropelDateTime::newInstance('now');

		$second = $this->getEndAt()->getTimestamp() - $startAt->getTimestamp();
		return $second;
	}

	public function setRemainSecond($second) {
		$startAt = $this->isPause()
			? $this->getPauseAt()
			: PropelDateTime::newInstance('now');

		$this->setEndAt(intval($startAt->getTimestamp() + $second));
	}

	public function isTimeUp() {
		$now = PropelDateTime::newInstance('now');
		return !$this->isPause() && $this->getEndAt()->getTimestamp() < $now->getTimestamp();
	}
}
