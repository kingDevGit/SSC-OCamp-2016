<?php namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Model\Player;
use App\Model\PlayerQuery;
use App\Model\SettingQuery;
use App\Model\UnionQuery;
use App\Pusher;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController {
	protected $settings = array();

	public function __construct() {
		$this->settings = SettingQuery::create()->toAssoc();
	}

	public function getSettings(Request $request) {
		return response()->json($this->settings);
	}

	public function online(Request $request, Pusher $pusher) {
		$input = $request->only(['sid', 'admin']);

		if (empty($input['sid'])) {
			throw new ValidationException();
		}

		$player = $request->user();

		if ($input['admin'] === true && $player->hasTag(Player::TAG_ADMIN)) {
			$pusher->join($input['sid'], $player, true);
		} else {
			$pusher->join($input['sid'], $player);
		}

		return response()->json(['success' => true]);
	}

	public function offline(Request $request, Pusher $pusher) {
		$input = $request->only(['sid']);

		if (empty($input['sid'])) {
			throw new ValidationException();
		}

		$pusher->leave($input['sid']);

		return response()->json(['success' => true]);
	}

	public function scoreBoard(Request $request) {
		$result = [];

		$unions = UnionQuery::create()->find();
		foreach ($unions as $union) {
			$players = PlayerQuery::create()->findByUnionId($union->getId());

			$row = [];
			$row['group_name'] = $union->getName();
			$row['player_count'] = $players->count();
			$row['total_second'] = 0;
			$row['total_die_count'] = 0;

			foreach ($players as $player) {
				$second = $player->getRemainSecond();
				if ($second) {
					$row['total_second'] += $second;
				}

				$row['total_die_count'] += $player->getDieCount();
			}

			$row['average_second'] = $row['total_second'] / $row['player_count'];
			$row['average_die_count'] = $row['total_die_count'] / $row['player_count'];

			$result[] = $row;
		}

		return response()->json($result);
	}
}
