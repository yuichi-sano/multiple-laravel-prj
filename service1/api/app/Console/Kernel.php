<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use Nette\Utils\DateTime;
use packages\domain\model\batch\MigrationBatchAuditCriteria;
use packages\domain\model\batch\MigrationBatchAuditList;
use packages\domain\model\batch\MigrationBatchAuditRepository;
use packages\domain\model\batch\MigrationBatchAuditStatus;

class Kernel extends ConsoleKernel
{
    public function getMigrationList(): MigrationBatchAuditList
    {
        $migrationBatchAuditRepository = $this->app->make(MigrationBatchAuditRepository::class);
        return $migrationBatchAuditRepository->findAllMigration();
    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];
    protected $batchList = [
        'yuseiBatch' => ['command' => 'batch:yuseiMigration', 'schedule' => '']
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $list = $this->getMigrationList();
        foreach ($list as $migration) {
            if ($migration->getTargetTableName() == 'zips' || $migration->getTargetTableName(
                ) == 'yuseiyubinbangous' || $migration->getTargetTableName(
                ) == 'yuseiooguchijigyoushoyubinbangous') {
                $applyDate = $migration->getApplyDate()->getValue();
                $this->batchList['yuseiBatch']['schedule'] = $this->transCronSchedule($applyDate);
            }
        }

        foreach ($this->batchList as $key => $batch) {
            if ($batch['schedule'] != '') {
                $schedule->command($batch['command'])
                    ->cron($batch['schedule'])
                    ->runInBackground()
                    ->withoutOverlapping()
                    ->onOneServer()
                    ->before(function () use ($key) {
                        Log::info('The command by scheduler has been executed.', ['batch' => $key]);
                    })
                    ->onSuccess(function () use ($key) {
                        Log::info('The command by scheduler has been completed successfully.', ['batch' => $key]);
                    })
                    ->onFailure(function () use ($key) {
                        Log::error('The command by scheduler has been finished with error(s).', ['batch' => $key]);
                    });
            }
        }
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    protected function transCronSchedule(\DateTime $dateTime): string
    {
        $minute = $dateTime->format('i');
        $hour = $dateTime->format('H');
        $day = $dateTime->format('d');
        $month = $dateTime->format('m');
        return $minute . ' ' . $hour . ' ' . $day . ' ' . $month . ' *';
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
