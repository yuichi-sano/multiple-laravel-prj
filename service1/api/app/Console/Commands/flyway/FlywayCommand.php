<?php

namespace App\Console\Commands\Flyway;

use Illuminate\Console\ConfirmableTrait;

class FlywayCommand extends AbstractFlywayCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flyway {action=info} {location=local} {--TV|targetVersion=} {--test} {--force}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'flyway操作実行';

    public function handle()
    {
        $action = $this->argument("action");
        $location = $this->argument("location");
        $this->isTesting = $this->option('test');
        $this->cdFlyDir();
        $cmd = $this->flywayCmdBuild($action, $location, $this->option('targetVersion'));
        exec($cmd, $opt);
        print_r($opt);
    }
}
