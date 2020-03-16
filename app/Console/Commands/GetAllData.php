<?php

namespace App\Console\Commands;

use App\Jobs\GetSymbolList;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class GetAllData extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get All Updated Data';

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
        $this->dispatch((new GetSymbolList(true)));
    }
}
