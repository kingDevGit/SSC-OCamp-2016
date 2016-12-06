<?php namespace App\Model;

use App\Model\Base\SettingQuery as BaseSettingQuery;

class SettingQuery extends BaseSettingQuery {
	public function toAssoc() {
		$result = array();
		$settings = SettingQuery::create()->find();

		foreach ($settings as $setting) {
			$result[$setting->getName()] = $setting->getContent();
		}

		return $result;
	}
}
