<?php namespace App\Console\Commands;

use App\Model\SessionQuery;

class CleanupSessionCommand extends AbstractCommand {
	public $signature = 'session:cleanup';
	public $description = 'Remove expired sessions.';

	public function handle() {
		SessionQuery::create()->filterValid(false)->delete();
	}
}
