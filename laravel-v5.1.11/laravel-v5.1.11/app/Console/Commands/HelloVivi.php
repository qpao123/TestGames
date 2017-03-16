<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HelloVivi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zx {start=1} {end=10} {--mark=!}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $start = $this->argument('start');
        $end = $this->argument('end');
        $mark = $this->option('mark');

        echo $start.'==='.$end.'==='.$mark;
        echo 'nihao,vivi123';
    }
}
