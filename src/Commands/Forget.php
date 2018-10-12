<?php

namespace SevenLab\ResponseCache\Commands;

use Illuminate\Console\Command;
use SevenLab\ResponseCache\Facades\ResponseCache;

class Forget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'responsecache:forget {routeNames*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the specified cached responses';

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
        $routeNames = $this->argument('routeNames');

        ResponseCache::forget($routeNames);

        $this->info('Cleared specified cached responses.');
    }
}
