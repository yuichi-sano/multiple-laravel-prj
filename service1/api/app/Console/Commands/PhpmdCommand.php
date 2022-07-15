<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PhpmdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phpmd {file_path}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'phpmd';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $phpmd = base_path('vendor/phpmd/phpmd/src/bin/phpmd');
        echo shell_exec('php ' . $phpmd . ' ' . $this->argument('file_path') . ' text  phpmd.xml');
    }
}
