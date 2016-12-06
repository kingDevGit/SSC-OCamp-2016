<?php namespace App\Console\Commands;

use App\Pusher;
use Propel\Runtime\Propel;

class DeleteBombFromPlayerCommand extends PlayerCommand {
	public $signature = 'player:bomb-delete
		{--P|player=* : Player ID (Multiple)}';
	public $description = 'Delete player bomb';

	protected $con;
	protected $pusher;

	public function __construct(Pusher $pusher) {
		parent::__construct();
		$this->pusher = $pusher;
	}

	public function handle() {
		$this->con = Propel::getWriteConnection('chrono');
		$this->con->beginTransaction();

		try {
			$playerIds = $this->option('player');
			$this->deleteBomb($this->con, $playerIds);

			$this->con->commit();
			$this->info('Bomb deleted.');

			foreach ($playerIds as $playerId) {
				$this->pusher->addPlayerId($playerId);
			}

			$this->pusher->push();

		} catch (\Throwable $e) {
			$this->con->rollBack();
			throw $e;
		}
	}
}
