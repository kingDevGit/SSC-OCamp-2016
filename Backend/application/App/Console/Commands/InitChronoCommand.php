<?php namespace App\Console\Commands;

use App\Model\Account;
use App\Model\Player;
use App\Model\PlayerQuery;
use App\Model\Setting;
use Propel\Runtime\Propel;

class InitChronoCommand extends AbstractCommand {
	public $signature = 'chrono:init';
	public $description = 'Init settings & admin into the database if not already did';

	protected $con;

	public function handle() {
		$this->con = Propel::getWriteConnection('chrono');
		$this->con->beginTransaction();
		
		try {
			if (!isset($this->settings[Setting::GAME_STATUS_FIELD])) {
				$this->createSetting(Setting::GAME_STATUS_FIELD, Setting::GAME_STATUS_VALUE_BEFORE_EVENT);
			}

			if (!isset($this->settings[Setting::ALLOW_TRANSACTION_FIELD])) {
				$this->createSetting(Setting::ALLOW_TRANSACTION_FIELD, Setting::ALLOW_TRANSACTION_VALUE_NO);
			}

			if (!$this->hasAdmin()) {
				$player = $this->createAdmin(
					env('ADMIN_EMAIL', 'admin@admin.com'),
					env('ADMIN_PASSWORD', 'password'),
					env('ADMIN_NICKNAME', 'Terminal'),
					env('ADMIN_GENDER', 'male'),
					env('ADMIN_ADDRESS', 'admin')
				);

				if (!isset($this->settings[Setting::ADMIN_ID_FIELD])) {
					$this->createSetting(Setting::ADMIN_ID_FIELD, $player->getId());
				}
			}

			$this->con->commit();

		} catch (\Throwable $e) {
			$this->con->rollBack();
			throw $e;
		}
	}

	private function createSetting($name, $content) {
		$setting = new Setting();
		$setting->setName($name);
		$setting->setContent($content);
		$setting->save($this->con);
	}

	private function hasAdmin() {
		return PlayerQuery::create()->filterByTag(Player::TAG_ADMIN)->count() > 0;
	}

	private function createAdmin($email, $password, $nickname, $gender, $address) {
		$account = new Account();
		$account->setEmail($email);
		$account->setPassword($password);

		$player = new Player();
		$player->setNickname($nickname);
		$player->setGender($gender);
		$player->setAddress($address);
		$player->addTag(Player::TAG_ADMIN);
		$player->addTag(Player::TAG_OC);
		$player->setAccount($account);
		$player->save($this->con);

		return $player;
	}
}
