<?php namespace App\Console;

use App\Console\Commands\AddSecondToPlayerCommand;
use App\Console\Commands\CleanupSessionCommand;
use App\Console\Commands\CreatePlayerCommand;
use App\Console\Commands\CreateTimerToPlayerCommand;
use App\Console\Commands\CreateUnionCommand;
use App\Console\Commands\DeleteBombFromPlayerCommand;
use App\Console\Commands\HandleTimersCommand;
use App\Console\Commands\InitChronoCommand;
use App\Console\Commands\NotificationPusherCommand;
use App\Console\Commands\PlayerPusherCommand;
use App\Console\Commands\SettingsPusherCommand;
use App\Console\Commands\UnionPusherCommand;
use App\Console\Commands\UpdatePlayerCommand;
use App\Console\Commands\UpdateTimerToPlayerCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
	protected $commands = [
		InitChronoCommand::class,
		CleanupSessionCommand::class,
		HandleTimersCommand::class,
		CreateUnionCommand::class,
		CreatePlayerCommand::class,
		UpdatePlayerCommand::class,
		CreateTimerToPlayerCommand::class,
		UpdateTimerToPlayerCommand::class,
		AddSecondToPlayerCommand::class,
		DeleteBombFromPlayerCommand::class,
		PlayerPusherCommand::class,
		UnionPusherCommand::class,
		NotificationPusherCommand::class,
		SettingsPusherCommand::class,
	];

	protected function schedule(Schedule $schedule) {
		$schedule->command('session:cleanup')->everyFiveMinutes();

		$schedule->command('timers:handle')
			->everyMinute()
			->appendOutputTo('/var/www/storage/logs/timers_handler.log');
	}
}
