<?php namespace App\Console\Commands;

use App\Pusher;

class SettingsPusherCommand extends AbstractCommand {
	public $signature = 'pusher:settings';
	public $description = 'Force push settings.';

	protected $pusher;

	public function __construct(Pusher $pusher) {
		parent::__construct();
		$this->pusher = $pusher;
	}

	public function handle() {
		$this->pusher->pushSettings();
		$this->pusher->push();
	}
}
