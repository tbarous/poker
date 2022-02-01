<?php

namespace App\Jobs;

use App\Mail\FilteredProductList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailFilteredProductList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $products;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle()
    {
        $handle = fopen(storage_path('app/public/report.csv'), 'w');

        fputcsv($handle, ['Name', 'Barcode', 'Brand', 'Price', 'Date'], ';');

        foreach ($this->products as $product) {
            $temp = [
                $product['name'],
                $product['barcode'],
                $product['brand']['name'],
            ];

            if (!empty($product['brand']['name'])) {
                $temp[] = $product['brand']['name'];
            }

            $temp[] = $product['price'];
            $temp[] = $product['date_added'];

            fputcsv($handle, $temp, ';');
        }

        fclose($handle);

        Mail::to("")->queue(new FilteredProductList());

        return 0;
    }
}
