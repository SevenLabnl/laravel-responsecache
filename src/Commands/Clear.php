<?php

namespace SevenLab\ResponseCache\Commands;

use Illuminate\Console\Command;
use SevenLab\ResponseCache\Facades\ResponseCache;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'responsecache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the cached responses';

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
        ResponseCache::clear();

        $this->info('Cleared all cached responses.');
    }
}
