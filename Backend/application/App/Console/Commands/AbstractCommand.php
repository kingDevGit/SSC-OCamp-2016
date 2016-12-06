<?php namespace App\Console\Commands;

use App\Model\SettingQuery;
use Illuminate\Console\Command as BaseCommand;

abstract class AbstractCommand extends BaseCommand {
	protected $settings = array();

	public function __construct() {
		parent::__construct();

		$this->settings = SettingQuery::create()->toAssoc();
	}
}
