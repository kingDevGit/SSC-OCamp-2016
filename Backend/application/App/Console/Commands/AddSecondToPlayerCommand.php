<?php namespace App\Console\Commands;

use App\Pusher;
use Propel\Runtime\Propel;

class AddSecondToPlayerCommand extends PlayerCommand {
	public $signature = 'player:add-second
		{--P|player=* : Player ID (Multiple)}
		{--S|second= : Second}';
	public $description = 'Set player timers\' second';

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
			$this->addSecond($this->con, $playerIds, $this->option('second'));

			$this->con->commit();
			$this->info('Time added.');

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
