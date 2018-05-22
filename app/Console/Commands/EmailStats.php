<?php

namespace App\Console\Commands;

use App\Classes\Export;
use App\Jobs\ProcessExport;
use Illuminate\Console\Command;

class EmailStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:emailstats';

    protected $export;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates new csv and emails defined list of internal team';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Export $export)
    {
        parent::__construct();

        $this->export = $export;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //dispatch job
        ProcessExport::dispatch(true);
    }
}
