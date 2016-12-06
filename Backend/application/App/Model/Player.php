<?php namespace App\Model;

use App\Exceptions\TimerNotFoundException;
use App\Exceptions\ValidationException;
use App\Model\Base\Player as BasePlayer;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Map\TableMap;

class Player extends BasePlayer {
	const TAG_JOBA = 'joba';
	const TAG_JOMA = 'joma';
	const TAG_OC = 'oc';
	const TAG_ADMIN = 'admin';
	const TAG_BOMB_EXPLODED = 'bomb_exploded';

	private $skipRemain = false;

	public function preSave(ConnectionInterface $con = null) {
		if (!$this->validate()) {
			throw new ValidationException();
		}

		if ($this->getTimers()->count() > 2) {
			throw new ValidationException();
		}

		$remain = $this->getRemainSecond();
		if ($this->skipRemain === false && $remain !== null && $remain < 0) {
			throw new ValidationException();
		}

		$tags = array_unique($this->getTags());
		$this->setTags($tags);

		return true;
	}

	public function postSave(ConnectionInterface $con = null) {
		$this->clearTimers();
	}

	public function toAssoc() {
		$assoc = $this->toArray(TableMap::TYPE_FIELDNAME);

		$assoc['union'] = null;
		$union = $this->getUnion();
		if ($union !== null) {
			$assoc['union'] = $union->toAssoc();
		}

		$assoc['timers'] = [];
		$timers = $this->getTimers();
		foreach ($timers as $timer) {
			$assoc['timers'][] = $timer->toAssoc();
		}

		return $assoc;
	}

	public function skipRemainCheck($allow = true) {
		$this->skipRemain = $allow;
	}

	public function getPrimaryTimer() {
		$timers = $this->getTimers();
		if ($timers->count() < 1) return null;

		foreach ($timers as $timer) {
			if (get_class($timer) === Bomb::class) {
				return $timer;
			}
		}

		return $timers->getFirst();
	}

	public function getSecondaryTimer() {
		$timers = $this->getTimers();
		if ($timers->count() < 2) return null;

		foreach ($timers as $timer) {
			if (get_class($timer) === Timer::class) {
				return $timer;
			}
		}

		return $timers->getFirst();
	}

	public function getRemainSecond() {
		$timer = $this->getPrimaryTimer();
		return $timer ? $timer->getRemainSecond() : null;
	}

	public function setRemainSecond($second) {
		$timer = $this->getPrimaryTimer();

		if ($timer) {
			$timer->setRemainSecond($second);
		} else {
			throw new TimerNotFoundException();
		}
	}
}
