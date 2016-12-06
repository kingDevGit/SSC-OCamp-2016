<?php namespace App\Model;

use App\Model\Base\Notification as BaseNotification;
use Propel\Runtime\Map\TableMap;

class Notification extends BaseNotification {
	const MESSAGE_TRANSACTION_PLAYER_A = '成功傳送 %2$d 秒予 %1$s。';
	const MESSAGE_TRANSACTION_PLAYER_B_ADD = '成功收取來自 %1$s 的 %2$d 秒。';
	const MESSAGE_TRANSACTION_PLAYER_B_DEDUCT = '成功被 %1$s 扣除 %2$d 秒。';
	const MESSAGE_PLAYER_DIE_ADD = '你死亡一次了，每個 %1$s 的組員都提供了 10%% 時間給你復活，你共獲得 %2$d 秒。';
	const MESSAGE_PLAYER_DIE_DEDUCT = '你的組員 %2$s 死亡了，每個 %1$s 的組員都提供了 10%% 時間給死者復活，你提供了 %3$d 秒。';
	const MESSAGE_PLAYER_DIE_PENALTY = '已扣除死亡懲罰 %1$d 秒。';
	const MESSAGE_PLAYER_DIE_NO_UNION = '你死亡一次了。';
	const MESSAGE_BOMB_EXPLODED = '你的炸彈已經爆破，你死亡一次了。';
	const MESSAGE_REWARD_ADMIN = '%1$s 對 %2$s 調整了 %3$d 秒。';
	const MESSAGE_REWARD_PLAYER = '%1$s 對你調整了 %2$d 秒。';
	const MESSAGE_KILL_ADMIN = '%1$s 殺死了 %2$s。';
	const MESSAGE_KILL_PLAYER = '%1$s 殺死了你。';

	public function toAssoc() {
		return $this->toArray(TableMap::TYPE_FIELDNAME);
	}
}
