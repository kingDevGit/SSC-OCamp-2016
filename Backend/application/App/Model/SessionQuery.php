<?php namespace App\Model;

use App\Model\Base\SessionQuery as BaseSessionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Util\PropelDateTime;

class SessionQuery extends BaseSessionQuery {
	public function filterValid($valid = true) {
		return $this->filterByExpiredAt(
			PropelDateTime::newInstance('now'),
			$valid === true ? Criteria::GREATER_EQUAL : Criteria::LESS_THAN
		);
	}
}
