<?php namespace App\Http\Middleware;

use App\Exceptions\TokenNotValidException;
use App\Model\SessionQuery;
use Illuminate\Http\Request as Request;

class AuthMiddleware {
	const KEY = 'auth';

	private $session;

	public function handle(Request $request, \Closure $next) {
		$token = $request->header('Chrono-Token');

		$this->session = SessionQuery::create()
			->filterByPrimaryKey($token)
			->filterValid(true)
			->findOne();

		if ($this->session) {
			$player = $this->session->getPlayer();
			$request->attributes->set('$session', $this->session);
			$request->setUserResolver(function () use ($player) {
				return $player;
			});

			$response = $next($request);

			try {
				$this->session->deferExpiredAt();
				$this->session->save();
			} catch (\Throwable $e) {
			}

			return $response;
		}

		throw new TokenNotValidException();
	}
}
