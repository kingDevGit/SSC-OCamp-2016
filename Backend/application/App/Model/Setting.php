<?php namespace App\Model;

use App\Model\Base\Setting as BaseSetting;

class Setting extends BaseSetting {
	const GAME_STATUS_FIELD = 'game_status';
	const GAME_STATUS_VALUE_BEFORE_EVENT = 'before_event';
	const GAME_STATUS_VALUE_EVENT = 'event';
	const GAME_STATUS_VALUE_GAME = 'game';
	const GAME_STATUS_VALUE_AFTER_EVENT = 'after_event';

	const ALLOW_TRANSACTION_FIELD = 'allow_transaction';
	const ALLOW_TRANSACTION_VALUE_YES = 'yes';
	const ALLOW_TRANSACTION_VALUE_NO = 'no';

	const ADMIN_ID_FIELD = 'admin_id';
}
