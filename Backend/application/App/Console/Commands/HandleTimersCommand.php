<?php namespace App\Console\Commands;

use App\Model\Bomb;
use App\Model\Notification;
use App\Model\Player;
use App\Model\PlayerQuery;
use App\Model\Setting;
use App\Model\Timer;
use App\Model\TimerQuery;
use App\Model\Transaction;
use App\Pusher;
use Propel\Runtime\Propel;

class HandleTimersCommand extends AbstractCommand {
	public $signature = 'timers:handle';
	public $description = 'Handle time-up timers';

	protected $con;
	protected $pusher;

	private $admin;
	private $pushPlayerIds = [];
	private $pushUnionIds = [];
	private $pushNotificationIds = [];

	public function __construct(Pusher $pusher) {
		parent::__construct();
		$this->pusher = $pusher;
	}

	protected function push() {
		foreach ($this->pushPlayerIds as $id) {
			$this->pusher->addPlayerId($id);
		}

		foreach ($this->pushUnionIds as $id) {
			$this->pusher->addUnionId($id);
		}

		foreach ($this->pushNotificationIds as $id) {
			$this->pusher->addNotificationId($id);
		}

		$this->pusher->push();
	}

	public function handle() {
		$this->con = Propel::getWriteConnection('chrono');
		$this->con->beginTransaction();

		try {
			$admin_id = $this->settings[Setting::ADMIN_ID_FIELD];
			$this->admin = PlayerQuery::create()->findOneById($admin_id);

			$this->handleTimeUps();
			$this->con->commit();
			$this->push();

		} catch (\Throwable $e) {
			$this->con->rollBack();
			throw $e;
		}
	}

	public function handleTimeUps() {
		$timers = TimerQuery::create()->filterTimeUpFilter()->find();

		foreach ($timers as $obj) {
			if (get_class($obj) === Bomb::class) {
				$this->handleBombTimeUp($obj);
			} else if (get_class($obj) === Timer::class) {
				$this->handleTimerTimeUp($obj);
			}
		}
	}

	public function handleBombTimeUp(Bomb $bomb) {
		if ($bomb->isTimeUp()) {
			$player = $bomb->getPlayer();
			$player->setDieCount($player->getDieCount() + 1);
			$player->addTag(Player::TAG_BOMB_EXPLODED);
			$player->getSecondaryTimer()->start();
			$player->skipRemainCheck(true);
			$player->save($this->con);

			$bomb->delete($this->con);

			$notification = new Notification();
			$notification->setPlayer($player);
			$notification->setMessage(Notification::MESSAGE_BOMB_EXPLODED);
			$notification->save($this->con);

			$this->pushPlayerIds[] = $player->getId();
			$this->pushNotificationIds[] = $notification->getId();
		}
	}

	public function handleTimerTimeUp(Timer $timer) {
		if ($timer->isTimeUp()) {
			$player = $timer->getPlayer();
			$player->setDieCount($player->getDieCount() + 1);
			$player->skipRemainCheck(true);
			$player->save($this->con);

			$union = $player->getUnion();

			if (!$union) {
				$timer->delete($this->con);

				$notification = new Notification();
				$notification->setPlayer($player);
				$notification->setMessage(Notification::MESSAGE_PLAYER_DIE_NO_UNION);
				$notification->save($this->con);

				$this->pushNotificationIds[] = $notification->getId();

			} else {
				$mates = $union->getPlayers();
				$sum = 0;

				foreach ($mates as $mate) {
					$mateSecond = $mate->getRemainSecond();

					if ($mateSecond && $mateSecond > 0) {
						$mateSecond = intval($mateSecond / 10);
						$sum += $mateSecond;

						if ($mate->getId() === $player->getId()) {
							continue;
						}

						$transaction = new Transaction();
						$transaction->setPlayerRelatedByPlayerA($mate);
						$transaction->setPlayerRelatedByPlayerB($player);
						$transaction->setSecond($mateSecond);
						$transaction->execute($this->con);

						$message = sprintf(
							Notification::MESSAGE_PLAYER_DIE_DEDUCT,
							$union->getName(),
							$player->getNickname(),
							$mateSecond
						);

						$notification = new Notification();
						$notification->setPlayer($mate);
						$notification->setMessage($message);
						$notification->save($this->con);

						$this->pushPlayerIds[] = $mate->getId();
						$this->pushNotificationIds[] = $notification->getId();
					}
				}

				$message = sprintf(
					Notification::MESSAGE_PLAYER_DIE_ADD,
					$union->getName(),
					$sum
				);

				$notification = new Notification();
				$notification->setPlayer($player);
				$notification->setMessage($message);
				$notification->save($this->con);

				$this->pushNotificationIds[] = $notification->getId();

				$transaction = new Transaction();
				$transaction->setPlayerRelatedByPlayerA($player);
				$transaction->setPlayerRelatedByPlayerB($this->admin);
				$transaction->setSecond(intval($sum * 0.2));
				$transaction->execute($this->con);

				$message = sprintf(
					Notification::MESSAGE_PLAYER_DIE_PENALTY,
					intval($sum * 0.2)
				);

				$notification = new Notification();
				$notification->setPlayer($player);
				$notification->setMessage($message);
				$notification->save($this->con);

				$this->pushNotificationIds[] = $notification->getId();
			}

			$this->pushPlayerIds[] = $player->getId();
		}
	}
}
