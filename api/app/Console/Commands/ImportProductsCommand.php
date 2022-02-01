<?php

namespace App\Console\Commands;

use App\Jobs\ProductsBulkImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert products to database';

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
    public function handle(): int
    {
        Queue::push(new ProductsBulkImport());

        return 0;
    }
}
