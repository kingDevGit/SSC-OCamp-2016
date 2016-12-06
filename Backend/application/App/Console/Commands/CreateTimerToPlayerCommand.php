<?php namespace App\Console\Commands;

use App\Pusher;
use Propel\Runtime\Propel;

class CreateTimerToPlayerCommand extends PlayerCommand {
	public $signature = 'player:timer-create
		{--P|player=* : Player ID (Multiple)}
		{--E|end= : End At}
		{--B|bomb : Is Bomb?}';
	public $description = 'Create timers';

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
			$this->createTimers($this->con, $playerIds, $this->option('end'), $this->option('bomb'));

			$this->con->commit();
			$this->info('Timer created.');

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
