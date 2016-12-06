<?php namespace App\Model;

use App\Model\Base\TimerQuery as BaseTimerQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Util\PropelDateTime;

class TimerQuery extends BaseTimerQuery {
	public function filterTimeUpFilter($timeUp = true) {
		return $timeUp === true
			? $this->filterByPause(false)->filterByEndAt(PropelDateTime::newInstance('now'), Criteria::LESS_THAN)
			: $this->_or()->filterByPause(true)->filterByEndAt(PropelDateTime::newInstance('now'), Criteria::GREATER_EQUAL);
	}
}
