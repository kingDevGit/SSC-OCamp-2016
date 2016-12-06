<?php namespace App\Console\Commands;

use App\Pusher;

class NotificationPusherCommand extends AbstractCommand {
	public $signature = 'pusher:notification
		{--N|notification=* : Notification ID (Multiple)}';
	public $description = 'Force push to notification.';

	protected $pusher;

	public function __construct(Pusher $pusher) {
		parent::__construct();
		$this->pusher = $pusher;
	}

	public function handle() {
		$notificationIds = $this->option('notification');

		foreach ($notificationIds as $notificationId) {
			$this->pusher->addNotificationId($notificationId);
		}

		$this->pusher->push();
	}
}
