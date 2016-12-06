<?php namespace App\Http\Middleware;

use App\Exceptions\PermissionDeniedException;
use App\Model\Player;
use Illuminate\Http\Request as Request;

class AdminMiddleware {
	const KEY = 'admin';

	public function handle(Request $request, \Closure $next) {
		$player = $request->user();

		if ($player->hasTag(Player::TAG_ADMIN)) {
			return $next($request);
		}

		throw new PermissionDeniedException();
	}
}
