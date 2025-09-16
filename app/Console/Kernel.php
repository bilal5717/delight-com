<?php
/**

 *
 * LICENSE
 * -------
 */

namespace App\Console;

use App\Console\Commands\ExportDatabaseTablesCommand;
use App\Console\Commands\ImportDatabaseTablesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		\App\Console\Commands\Inspire::class,
		\App\Console\Commands\AdsClear::class,
		\App\Console\Commands\Backup::class,
		\App\Console\Commands\BackupClear::class,
      	\App\Console\Commands\IndexingCron::class,
      	\App\Console\Commands\SwooleAutostartCommand::class,
      	\App\Console\Commands\CacheWarmUp::class,
        ExportDatabaseTablesCommand::class,
        ImportDatabaseTablesCommand::class,

	];
	
	/**
	 * Define the application's command schedule.
	 *
	 * @param \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// NOTE:
		// Scheduling Task Every Minutes: $schedule->command('signature')->cron('* * * * *'); // Only for tests purpose
		// Scheduling Task Every N Minutes: $schedule->command('signature')->cron('*/9 * * * *'); // every 9 minutes
		// Scheduling Task Every N Hours: $schedule->command('signature')->cron('0 */5 * * *'); // every 5 hours
		// Scheduling Task Every N Days: $schedule->command('signature')->cron('0 0 */3 * *'); // every 3 days
		
		// CLEAR ADS
		$adsClearEvent = $schedule->command('ads:clear')->hourly();
      
      	// Schecduling Task Every 5 minutes: $schedule->command('indexing:cron')->everyFiveMinutes();
		$schedule->command('indexing:cron')->cron('15 * * * *');
		$schedule->command('swoole:autostart')->cron('15 * * * *');
		
		// BACKUPS
		// $expression = '* * * * *';   // every minute (Only for tests purpose!)
		// $expression = '*/5 * * * *'; // every 5 minutes (Only for tests purpose!)
		if (config('settings.backup.backup_all')) {
			$days = (int)config('settings.backup.backup_frequency_all');
			if ($days > 0) {
				$expression = '0 0 */' . $days . ' * *'; // every N days
				$backupAllEvent = $schedule->command('makeBackup:exec')->cron($expression);
			}
		}
		if (config('settings.backup.backup_database')) {
			$days = (int)config('settings.backup.backup_frequency_database');
			if ($days > 0) {
				$expression = '0 0 */' . $days . ' * *'; // every N days
				$backupDatabaseEvent = $schedule->command('makeBackup:exec database')->cron($expression);
			}
		}
		if (config('settings.backup.backup_files')) {
			$days = (int)config('settings.backup.backup_frequency_files');
			if ($days > 0) {
				$expression = '0 0 */' . $days . ' * *'; // every N days
				$backupFilesEvent = $schedule->command('makeBackup:exec files')->cron($expression);
			}
		}
		if (config('settings.backup.backup_languages')) {
			$days = (int)config('settings.backup.backup_frequency_languages');
			if ($days > 0) {
				$expression = '0 0 */' . $days . ' * *'; // every N days
				$backupLanguagesEvent = $schedule->command('makeBackup:exec languages')->cron($expression);
			}
		}
		
		// BACKUPS (CLEANUP)
		if (config('settings.backup.backup_cleanup_keep_days')) {
			$days = (int)config('settings.backup.backup_cleanup_keep_days');
			if ($days > 0) {
				// $backupCleanupEvent = $schedule->command('clearBackup:exec')->hourly(); // every hours (Only for tests purpose!)
				$backupCleanupEvent = $schedule->command('clearBackup:exec')->daily();
			}
		}
		
		// CLEAR CACHE & VIEWS
		if (!env('DISABLE_CACHE_AUTO_CLEAR') || (int)env('DISABLE_CACHE_AUTO_CLEAR', 0) != 1) {
			$cacheClearEvent1 = $schedule->command('cache:clear')->weeklyOn(7, '6:00');
			$cacheClearEvent2 = $schedule->command('cache:clear')->weeklyOn(7, '6:05'); // To prevent file lock issues (Optional)
			$viewClearEvent = $schedule->command('view:clear')->weeklyOn(7, '6:00');
		}
	}
	
	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		require base_path('routes/console.php');
	}
}
