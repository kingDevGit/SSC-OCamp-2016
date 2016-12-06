<?php namespace App\Model;

use App\Model\Map\TimerTableMap;

class Bomb extends Timer {
	public function __construct() {
		parent::__construct();
		$this->setClassKey(TimerTableMap::CLASSKEY_BOMB);
	}
}
