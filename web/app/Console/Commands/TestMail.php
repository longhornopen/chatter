<?php

namespace App\Console\Commands;

use App\Mail\Test;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatter:mail_test {email_address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test mail to an address';

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
        Mail::to($this->argument('email_address'))
            ->send(new Test());
        return 0;
    }
}
