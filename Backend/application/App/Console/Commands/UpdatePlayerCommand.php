<?php namespace App\Console\Commands;

use App\Pusher;
use Propel\Runtime\Propel;

class UpdatePlayerCommand extends PlayerCommand {
	public $signature = 'player:update {id : The id of player}
		{--N|nickname= : Nickname}
		{--G|gender= : Gender (male, female)}
		{--U|union= : Union id}
		{--T|tag=* : Tag (Multiple)}
		{--A|address= : Address}
		{--E|email= : Email}
		{--P|password= : Password}';
	public $description = 'Update player';

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
			$player = $this->updatePlayer($this->con, $this->argument('id'), [
				'nickname' => $this->option('nickname'),
				'gender' => $this->option('gender'),
				'union' => intval($this->option('union')),
				'tags' => $this->option('tag'),
				'address' => $this->option('address'),
				'email' => $this->option('email'),
				'password' => $this->option('password')
			]);

			$this->con->commit();
			$this->info('Player updated');

			$this->pusher->addPlayerId($player->getId());
			$this->pusher->push();

		} catch (\Throwable $e) {
			$this->con->rollBack();
			throw $e;
		}
	}
}
