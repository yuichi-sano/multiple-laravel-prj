<?php

namespace App\Console\Commands\Batch;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use packages\service\batch\YuseiMigrationService;

class YuseiMigrationCommand extends Command
{
    use ConfirmableTrait;

    private YuseiMigrationService $migrationService;

    /**
     * Create a new migration command instance.
     * @return void
     */
    public function __construct(YuseiMigrationService $yuseiMigrationService)
    {
        $this->migrationService = $yuseiMigrationService;
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:yuseiMigration';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'yuseiマスタマイグレーション実行';

    public function handle()
    {
        $this->info('start');
        try {
            $migrationList = $this->migrationService->getMigrationList();
            $this->info('getList');
        } catch (Exception $e) {
            $this->info($e->getMessage());
            return;
        }
        try {
            $this->migrationService->execute($migrationList);
            $this->info('done');
        } catch (Exception $e) {
            $this->info($e->getMessage());
            $this->info('fail');
            $this->migrationService->fail($migrationList);
        }
        $this->info('end');
    }
}
