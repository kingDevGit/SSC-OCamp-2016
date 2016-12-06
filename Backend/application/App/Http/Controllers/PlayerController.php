<?php namespace App\Http\Controllers;

use App\Exceptions\UnionNotFoundException;
use App\Model\NotificationQuery;
use Illuminate\Http\Request;

class PlayerController extends Controller {
	public function getMe(Request $request) {
		return response()->json($request->user()->toAssoc());
	}

	public function getNotifications(Request $request) {
		$input = $request->only(['min', 'max']);
		$query = NotificationQuery::create()->filterByPlayer($request->user());

		if (isset($input['min']) || isset($input['max'])) {
			$query->filterByCreatedAt($input);
		}

		$notifications = $query->find();
		$result = [];

		foreach ($notifications as $notification) {
			$result[] = $notification->toAssoc();
		}

		return response()->json($result);
	}
}
