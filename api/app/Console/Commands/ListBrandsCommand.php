<?php

namespace App\Console\Commands;

use App\Models\Brand;
use Illuminate\Console\Command;

class ListBrandsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brands:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Brands';

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
     * @return string
     */
    public function handle(): string
    {
        $brands = Brand::all();

        print_r($brands->toJson());

        return 0;
    }
}
