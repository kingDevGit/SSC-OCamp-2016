<?php namespace App\Console\Commands;

use App\Pusher;

class UnionPusherCommand extends AbstractCommand {
	public $signature = 'pusher:union
		{--U|union=* : Union ID (Multiple)}';
	public $description = 'Force push to union.';

	protected $pusher;

	public function __construct(Pusher $pusher) {
		parent::__construct();
		$this->pusher = $pusher;
	}

	public function handle() {
		$unionIds = $this->option('union');

		foreach ($unionIds as $unionId) {
			$this->pusher->addUnionId($unionId);
		}

		$this->pusher->push();
	}
}
