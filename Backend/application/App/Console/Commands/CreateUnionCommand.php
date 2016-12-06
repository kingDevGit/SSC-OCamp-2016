<?php namespace App\Console\Commands;

use App\Model\Union;
use Propel\Runtime\Propel;

class CreateUnionCommand extends AbstractCommand {
	public $signature = 'union:create
		{--N|name= : Name}
		{--C|color=#FFFFFF : Color (#FFFFFF)}';
	public $description = 'Create union';

	protected $con;

	public function handle() {
		$this->con = Propel::getWriteConnection('chrono');
		$this->con->beginTransaction();

		try {
			$union = new Union();
			$union->setName($this->option('name'));
			$union->setColor($this->option('color'));
			$union->save($this->con);

			$this->con->commit();
			$this->info('Union created. ID: ' . $union->getId());

		} catch (\Throwable $e) {
			$this->con->rollBack();
			throw $e;
		}
	}
}
