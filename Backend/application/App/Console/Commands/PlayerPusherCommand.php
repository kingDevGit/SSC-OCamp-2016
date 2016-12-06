<?php namespace App\Console\Commands;

use App\Pusher;

class PlayerPusherCommand extends AbstractCommand {
	public $signature = 'pusher:player
		{--P|player=* : Player ID (Multiple)}';
	public $description = 'Force push to player.';

	protected $pusher;

	public function __construct(Pusher $pusher) {
		parent::__construct();
		$this->pusher = $pusher;
	}

	public function handle() {
		$playerIds = $this->option('player');

		foreach ($playerIds as $playerId) {
			$this->pusher->addPlayerId($playerId);
		}

		$this->pusher->push();
	}
}
