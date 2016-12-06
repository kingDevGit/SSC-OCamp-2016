<?php namespace App\Http\Controllers\Admin;

use App\Model\SettingQuery;
use App\Pusher;
use Illuminate\Http\Request;
use Propel\Runtime\Propel;

class SettingsController extends Controller {
	public function setSettings(Request $request, Pusher $pusher) {
		$entries = $request->input();

		$con = Propel::getWriteConnection('chrono');
		$con->beginTransaction();

		try {
			foreach ($entries as $name => $content) {
				if (isset($this->settings[$name]) && $this->settings[$name] !== $content) {
					$setting = SettingQuery::create()->findOneByName($name);
					$setting->setContent($content);
					$setting->save($con);

					$pusher->updateSettings();
				}
			}

			$con->commit();
			$pusher->push();

			return response()->json(['success' => true]);

		} catch (\Throwable $e) {
			$con->rollBack();
			throw $e;
		}
	}
}
