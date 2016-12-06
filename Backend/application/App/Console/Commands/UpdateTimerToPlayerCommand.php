<?php namespace App\Console\Commands;

use App\Pusher;
use Propel\Runtime\Propel;

class UpdateTimerToPlayerCommand extends PlayerCommand {
	public $signature = 'player:timer-update
		{--P|player=* : Player ID (Multiple)}
		{--E|end= : End At}';
	public $description = 'Update player timers';

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
			$this->updateTimers($this->con, $playerIds, $this->option('end'));

			$this->con->commit();
			$this->info('Timer updated.');

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
