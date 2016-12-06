<?php namespace App\Http\Controllers;

use App\Exceptions\EmailPasswordNotMatchException;
use App\Exceptions\ValidationException;
use App\Model\AccountQuery;
use App\Model\Session;
use Illuminate\Http\Request;

class AuthController extends Controller {
	public function login(Request $request) {
		$input = $request->only(['email', 'password']);

		$account = AccountQuery::create()->findOneByEmail($input['email']);
		if (!$account || !$account->verifyPassword($input['password'])) {
			throw new EmailPasswordNotMatchException();
		}

		$session = new Session();
		$session->generateToken();
		$session->deferExpiredAt();

		$player = $account->getPlayer();
		$player->addSession($session);
		$player->save();

		return response()->json($session->toAssoc());
	}

	public function verify(Request $request) {
		$session = $request->attributes->get('$session');

		return response()->json($session->toAssoc());
	}

	public function logout(Request $request) {
		$session = $request->attributes->get('$session');
		$session->delete();

		return response()->json(['success' => true]);
	}
}
