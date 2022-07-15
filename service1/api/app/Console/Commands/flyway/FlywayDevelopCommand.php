<?php

namespace App\Console\Commands\Flyway;

use Illuminate\Console\ConfirmableTrait;

class FlywayDevelopCommand extends AbstractFlywayCommand
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flyway:develop {--force}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '開発用flywayマイグレーションwrapper';

    public function handle()
    {
        $this->cdFlyDir();
        if ($this->option('force')) {
            $this->clean();
        }
        $cmd = $this->flywayCmdBuild('migrate', 'local');
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
