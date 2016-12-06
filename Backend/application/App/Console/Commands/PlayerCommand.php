<?php namespace App\Console\Commands;

use App\Exceptions\PlayerNotFoundException;
use App\Model\Account;
use App\Model\Bomb;
use App\Model\Player;
use App\Model\PlayerQuery;
use App\Model\Timer;

abstract class PlayerCommand extends AbstractCommand {
	public function createPlayer($con, $param) {
		$account = new Account();
		$account->setEmail($param['email']);
		$account->setPassword($param['password']);

		$player = new Player();
		$player->setNickname($param['nickname']);
		$player->setGender($param['gender']);
		$player->setUnionId($param['union']);
		$player->setTags($param['tags']);
		$player->setAddress($param['address']);
		$player->setAccount($account);
		$player->save($con);

		return $player;
	}

	public function updatePlayer($con, $id, $param) {
		$player = PlayerQuery::create()->findOneById($id);

		if (!$player) {
			throw new PlayerNotFoundException();
		}

		if (!empty($param['nickname'])) {
			$player->setNickname($param['nickname']);
		}

		if (!empty($param['gender'])) {
			$player->setGender($param['gender']);
		}

		if (!empty($param['tags'])) {
			$player->setTags($param['tags']);
		}

		if (!empty($param['address'])) {
			$player->setAddress($param['address']);
		}

		if (!empty($param['email'])) {
			$player->getAccount()->setEmail($param['email']);
		}

		if (!empty($param['password'])) {
			$player->getAccount()->setPassword($param['password']);
		}

		$player->save($con);

		return $player;
	}

	public function createTimers($con, $playerIds, $endAt, $isBomb = false) {
		$timerTemplate = $isBomb ? new Bomb() : new Timer();
		$timerTemplate->setEndAt($endAt);

		$players = PlayerQuery::create()->findPks($playerIds);

		foreach ($players as $player) {
			$player->skipRemainCheck(true);
			$player->addTimer($timerTemplate->copy());

			$secondaryTimer = $player->getSecondaryTimer();
			if ($isBomb && $secondaryTimer) {
				$secondaryTimer->pause();
			}

			$player->save($con);
		}
	}

	public function updateTimers($con, $playerIds, $endAt) {
		$players = PlayerQuery::create()->findPks($playerIds);

		foreach ($players as $player) {
			$player->skipRemainCheck(true);

			$timer = $player->getPrimaryTimer();
			if ($timer) {
				$timer->setEndAt($endAt);
			}

			$player->save($con);
		}
	}

	public function addSecond($con, $playerIds, $second) {
		$players = PlayerQuery::create()->findPks($playerIds);

		foreach ($players as $player) {
			$player->skipRemainCheck(true);

			$timer = $player->getPrimaryTimer();
			if ($timer) {
				$timer->setRemainSecond($timer->getRemainSecond() + $second);
			}

			$player->save($con);
		}
	}

	public function deleteBomb($con, $playerIds) {
		$players = PlayerQuery::create()->findPks($playerIds);

		foreach ($players as $player) {
			$bomb = $player->getPrimaryTimer();
			$timer = $player->getSecondaryTimer();

			if ($bomb && get_class($bomb) === Bomb::class) {
				if ($timer) {
					$timer->start();
					$timer->save($con);
				}

				$bomb->delete($con);
				$player->reload(true);
			}
		}
	}
}
