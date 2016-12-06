<?php namespace App\Console\Commands;

use Propel\Runtime\Propel;

class CreatePlayerCommand extends PlayerCommand {
	public $signature = 'player:create
		{--N|nickname= : Nickname}
		{--G|gender= : Gender (male, female)}
		{--U|union= : Union id}
		{--T|tag=* : Tag (Multiple)}
		{--A|address= : Address}
		{--E|email= : Email}
		{--P|password= : Password}';
	public $description = 'Create player';

	protected $con;

	public function handle() {
		$this->con = Propel::getWriteConnection('chrono');
		$this->con->beginTransaction();

		try {
			$player = $this->createPlayer($this->con, [
				'nickname' => $this->option('nickname'),
				'gender' => $this->option('gender'),
				'union' => intval($this->option('union')),
				'tags' => $this->option('tag'),
				'address' => $this->option('address'),
				'email' => $this->option('email'),
				'password' => $this->option('password')
			]);

			$this->con->commit();
			$this->info('Player created. ID: ' . $player->getId());

		} catch (\Throwable $e) {
			$this->con->rollBack();
			throw $e;
		}
	}
}
