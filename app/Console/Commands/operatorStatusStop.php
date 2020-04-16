<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class operatorStatusStop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operatorStatus:stop {phone}';

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
      $phone = (int)$this->argument('phone');
      echo shell_exec('ssh root@192.168.66.245 "asterisk -x \'queue pause member Local/'.$phone.'@customer-survey-ivr/n\'"');
     // echo shell_exec('ssh root@192.168.66.245 "asterisk -x \'queue pause member Local/'.$phone.'@from-queue/n\'"');
    }
}
