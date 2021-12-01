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
    protected $signature = 'flyway {action=info} {location=local} {--test} {--force}';

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
        $test = $this->option('test');
        $this->cdFlyDir();
        $cmd =  $this->flywayCmdBuild($action,$location, $test);
        //print_r($cmd);
        exec($cmd, $opt);
        print_r($opt);
    }

}
