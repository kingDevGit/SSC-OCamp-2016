<?php namespace App;

use App\Model\NotificationQuery;
use App\Model\Player;
use App\Model\PlayerQuery;
use App\Model\SettingQuery;
use App\Model\UnionQuery;
use SocketIO\Emitter;

class Pusher {
	const REDIS_CHANNEL = 'php#action';
	const ROOM_PLAYER = 'player_';
	const ROOM_UNION = 'union_';
	const ROOM_SYSTEM = 'system';
	const ROOM_ADMIN = 'admin';

	const EVENT_PLAYER = 'player';
	const EVENT_UNION = 'union';
	const EVENT_NOTIFICATION = 'notification';
	const EVENT_SETTINGS = 'settings';

	private $emitter;
	private $redis;

	private $playerIds = [];
	private $unionIds = [];
	private $notificationIds = [];
	private $settings = false;

	public function __construct(Emitter $emitter) {
		$this->emitter = $emitter;
		$this->redis = $this->emitter->redis;
	}

	public function addPlayerId($id) {
		$this->playerIds[] = $id;
	}

	public function addUnionId($id) {
		$this->unionIds[] = $id;
	}

	public function addNotificationId($id) {
		$this->notificationIds[] = $id;
	}

	public function updateSettings() {
		$this->settings = true;
	}

	public function subscribe($sid, $room) {
		$this->redis->publish(self::REDIS_CHANNEL, "join,$sid,$room");
	}

	public function unsubscribe($sid) {
		$this->redis->publish(self::REDIS_CHANNEL, "leaveAll,$sid");
	}

	public function join($sid, Player $player, $admin = false) {
		if ($admin === true) {
			$this->subscribe($sid, self::ROOM_ADMIN);
		} else {
			$this->subscribe($sid, self::ROOM_PLAYER . $player->getId());
			$this->subscribe($sid, self::ROOM_UNION . $player->getUnionId());
			$this->subscribe($sid, self::ROOM_SYSTEM);
		}
	}

	public function leave($sid) {
		$this->unsubscribe($sid);
	}

	public function push() {
		if (count($this->playerIds)) $this->pushPlayers();
		if (count($this->unionIds)) $this->pushUnions();
		if (count($this->notificationIds)) $this->pushNotifications();
		if ($this->settings === true) $this->pushSettings();
	}

	public function pushPlayers(array $playerIds = []) {
		if (count($playerIds) === 0) $playerIds = $this->playerIds;

		$players = PlayerQuery::create()->findPks($playerIds);

		foreach ($players as $player) {
			$this->emitter
				->to(self::ROOM_ADMIN)
				->to(self::ROOM_PLAYER . $player->getId())
				->emit(self::EVENT_PLAYER, $player->toAssoc());
		}
	}

	public function pushUnions(array $unionIds = []) {
		if (count($unionIds) === 0) $unionIds = $this->unionIds;

		$unions = UnionQuery::create()->findPks($unionIds);

		foreach ($unions as $union) {
			$this->emitter
				->to(self::ROOM_ADMIN)
				->to(self::ROOM_UNION . $union->getId())
				->emit(self::EVENT_UNION, $union->toAssoc());
		}
	}

	public function pushNotifications(array $notificationIds = []) {
		if (count($notificationIds) === 0) $notificationIds = $this->notificationIds;

		$notifications = NotificationQuery::create()->findPks($notificationIds);

		foreach ($notifications as $notification) {
			$this->emitter
				->to(self::ROOM_ADMIN)
				->to(self::ROOM_PLAYER . $notification->getToPlayer())
				->emit(self::EVENT_NOTIFICATION, $notification->toAssoc());
		}
	}

	public function pushSettings() {
		$settings = SettingQuery::create()->toAssoc();

		$this->emitter
			->to(self::ROOM_ADMIN)
			->to(self::ROOM_SYSTEM)
			->emit(self::EVENT_SETTINGS, $settings);
	}
}
