<?php namespace App\Model;

use App\Model\Base\Union as BaseUnion;
use Propel\Runtime\Map\TableMap;

class Union extends BaseUnion {
	public function toAssoc() {
		return $this->toArray(TableMap::TYPE_FIELDNAME);
	}
}
