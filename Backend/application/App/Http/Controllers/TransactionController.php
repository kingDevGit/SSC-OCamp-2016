<?php namespace App\Http\Controllers;

use App\Exceptions\ViolateSettingsException;
use App\Exceptions\ValidationException;
use App\Model\Notification;
use App\Model\Player;
use App\Model\PlayerQuery;
use App\Model\Setting;
use App\Model\Transaction;
use App\Pusher;
use Illuminate\Http\Request;
use Propel\Runtime\Propel;

class TransactionController extends Controller {
	public function create(Request $request, Pusher $pusher) {
		$input = $request->only(['address', 'second']);

		if (empty($input['address']) || empty($input['second'])) {
			throw new ValidationException();
		}

		$con = Propel::getWriteConnection('chrono');
		$con->beginTransaction();

		try {
			$second = intval($input['second']);

			$playerA = $request->user();
			$playerB = PlayerQuery::create()->findOneByAddress($input['address']);

			$allowTransaction = $this->settings[Setting::ALLOW_TRANSACTION_FIELD] === Setting::ALLOW_TRANSACTION_VALUE_YES;
			$playerAIsOc = $playerA->hasTag(Player::TAG_OC);

			if ($allowTransaction === false && $playerAIsOc === false) {
				throw new ViolateSettingsException();
			}

			if ($playerAIsOc === false && $second <= 0) {
				throw new ValidationException();
			}

			$transaction = new Transaction();
			$transaction->setPlayerRelatedByPlayerA($playerA);
			$transaction->setPlayerRelatedByPlayerB($playerB);
			$transaction->setSecond($second);
			$transaction->execute($con);

			$message1 = sprintf(Notification::MESSAGE_TRANSACTION_PLAYER_A, $playerB->getNickname(), $second);
			$message2 = sprintf(
				$second > 0
					? Notification::MESSAGE_TRANSACTION_PLAYER_B_ADD
					: Notification::MESSAGE_TRANSACTION_PLAYER_B_DEDUCT,
				$playerA->getNickname(),
				$second > 0 ? $second : -$second
			);

			$notification1 = new Notification();
			$notification1->setPlayer($playerA);
			$notification1->setMessage($message1);
			$notification1->save($con);

			$notification2 = new Notification();
			$notification2->setPlayer($playerB);
			$notification2->setMessage($message2);
			$notification2->save($con);

			$con->commit();

			$pusher->addPlayerId($playerA->getId());
			$pusher->addPlayerId($playerB->getId());
			$pusher->addNotificationId($notification1->getId());
			$pusher->addNotificationId($notification2->getId());
			$pusher->push();

			return response()->json(['success' => true]);

		} catch (\Throwable $e) {
			$con->rollback();
			throw $e;
		}
	}

	public function awardPlayer(Request $request, Pusher $pusher) {
		$input = $request->only(['address', 'second']);

		if (empty($input['address']) || empty($input['second'])) {
			throw new ValidationException();
		}

		$con = Propel::getWriteConnection('chrono');
		$con->beginTransaction();

		try {
			$second = intval($input['second']);

			$authPlayer = $request->user();
			if ($authPlayer->hasTag(Player::TAG_OC) === false) {
				throw new ViolateSettingsException();
			}

			$admin_id = $this->settings[Setting::ADMIN_ID_FIELD];
			$playerA = PlayerQuery::create()->findOneById($admin_id);
			$playerB = PlayerQuery::create()->findOneByAddress($input['address']);

			$transaction = new Transaction();
			$transaction->setPlayerRelatedByPlayerA($playerA);
			$transaction->setPlayerRelatedByPlayerB($playerB);
			$transaction->setSecond($second);
			$transaction->execute($con);

			$message1 = sprintf(
				Notification::MESSAGE_REWARD_ADMIN,
				$authPlayer->getNickname(),
				$playerB->getNickname(),
				$second
			);
			$message2 = sprintf(
				Notification::MESSAGE_REWARD_PLAYER,
				$authPlayer->getNickname(),
				$second
			);

			$notification1 = new Notification();
			$notification1->setPlayer($playerA);
			$notification1->setMessage($message1);
			$notification1->save($con);

			$notification2 = new Notification();
			$notification2->setPlayer($playerB);
			$notification2->setMessage($message2);
			$notification2->save($con);

			$con->commit();

			$pusher->addPlayerId($playerA->getId());
			$pusher->addPlayerId($playerB->getId());
			$pusher->addNotificationId($notification1->getId());
			$pusher->addNotificationId($notification2->getId());
			$pusher->push();

			return response()->json(['success' => true]);

		} catch (\Throwable $e) {
			$con->rollback();
			throw $e;
		}
	}

	public function awardUnion(Request $request, Pusher $pusher) {
		$input = $request->only(['union', 'second']);

		if (empty($input['union']) || empty($input['second'])) {
			throw new ValidationException();
		}

		$con = Propel::getWriteConnection('chrono');
		$con->beginTransaction();

		try {
			$second = intval($input['second']);

			$authPlayer = $request->user();
			if ($authPlayer->hasTag(Player::TAG_OC) === false) {
				throw new ViolateSettingsException();
			}

			$admin_id = $this->settings[Setting::ADMIN_ID_FIELD];
			$playerA = PlayerQuery::create()->findOneById($admin_id);

			$players = PlayerQuery::create()->findByUnionId($input['union']);
			$pushPlayerIds = [];
			$pushNotificationIds = [];

			foreach ($players as $playerB) {
				$transaction = new Transaction();
				$transaction->setPlayerRelatedByPlayerA($playerA);
				$transaction->setPlayerRelatedByPlayerB($playerB);
				$transaction->setSecond($second);
				$transaction->execute($con);

				$message1 = sprintf(
					Notification::MESSAGE_REWARD_ADMIN,
					$authPlayer->getNickname(),
					$playerB->getNickname(),
					$second
				);
				$message2 = sprintf(
					Notification::MESSAGE_REWARD_PLAYER,
					$authPlayer->getNickname(),
					$second
				);

				$notification1 = new Notification();
				$notification1->setPlayer($playerA);
				$notification1->setMessage($message1);
				$notification1->save($con);

				$notification2 = new Notification();
				$notification2->setPlayer($playerB);
				$notification2->setMessage($message2);
				$notification2->save($con);

				$pushPlayerIds[] = $playerA->getId();
				$pushPlayerIds[] = $playerB->getId();
				$pushNotificationIds[] = $notification1->getId();
				$pushNotificationIds[] = $notification2->getId();
			}

			$con->commit();

			foreach ($pushPlayerIds as $playerId) {
				$pusher->addPlayerId($playerId);
			}

			foreach ($pushNotificationIds as $notificationId) {
				$pusher->addNotificationId($notificationId);
			}

			$pusher->push();

			return response()->json(['success' => true]);

		} catch (\Throwable $e) {
			$con->rollback();
			throw $e;
		}
	}

	public function kill(Request $request, Pusher $pusher) {
		$input = $request->only(['address']);

		if (empty($input['address'])) {
			throw new ValidationException();
		}

		$con = Propel::getWriteConnection('chrono');
		$con->beginTransaction();

		try {
			$authPlayer = $request->user();
			if ($authPlayer->hasTag(Player::TAG_OC) === false) {
				throw new ViolateSettingsException();
			}

			$admin_id = $this->settings[Setting::ADMIN_ID_FIELD];
			$playerA = PlayerQuery::create()->findOneById($admin_id);
			$playerB = PlayerQuery::create()->findOneByAddress($input['address']);
			$second = -$playerB->getRemainSecond();

			$transaction = new Transaction();
			$transaction->setPlayerRelatedByPlayerA($playerA);
			$transaction->setPlayerRelatedByPlayerB($playerB);
			$transaction->setSecond($second);
			$transaction->execute($con);

			$message1 = sprintf(
				Notification::MESSAGE_KILL_ADMIN,
				$authPlayer->getNickname(),
				$playerB->getNickname()
			);
			$message2 = sprintf(
				Notification::MESSAGE_KILL_PLAYER,
				$authPlayer->getNickname()
			);

			$notification1 = new Notification();
			$notification1->setPlayer($playerA);
			$notification1->setMessage($message1);
			$notification1->save($con);

			$notification2 = new Notification();
			$notification2->setPlayer($playerB);
			$notification2->setMessage($message2);
			$notification2->save($con);

			$con->commit();

			$pusher->addPlayerId($playerA->getId());
			$pusher->addPlayerId($playerB->getId());
			$pusher->addNotificationId($notification1->getId());
			$pusher->addNotificationId($notification2->getId());
			$pusher->push();

			return response()->json(['success' => true]);

		} catch (\Throwable $e) {
			$con->rollback();
			throw $e;
		}
	}
}
