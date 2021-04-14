<?php

namespace App\Console\Commands;

use App\Events\BroadcastTested;
use Illuminate\Console\Command;

class TestBroadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test broadcast features by sending a message to clients, which will be console.log()\'ed';

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
     * @return int
     */
    public function handle()
    {
        event(new BroadcastTested());

        return 0;
    }
}
