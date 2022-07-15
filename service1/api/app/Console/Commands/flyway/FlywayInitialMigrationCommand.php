<?php

namespace App\Console\Commands\Flyway;

use Illuminate\Console\ConfirmableTrait;

class FlywayInitialMigrationCommand extends AbstractFlywayCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flyway:initMigration {--force}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '開発用初期実行flywayマイグレーションwrapper';

    public function handle()
    {
        $this->cdFlyDir();
        if ($this->option('force')) {
            $this->clean();
        }
        $cmd = $this->flywayCmdBuild('migrate', 'local', '1.9');
        exec($cmd, $opt);
        print_r($opt);
    }

    private function clean()
    {
        $cmd = $this->flywayCmdBuild('clean', 'local');
        exec($cmd, $opt);
        print_r($opt);
    }
}
