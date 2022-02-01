<?php

namespace App\Jobs;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductsBulkImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $brands = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle(): int
    {
        Cache::flush();

        $this->truncate();

        DB::transaction(function () {
            $file = fopen(storage_path('app/public/legacy_products.csv'), "r");

            Brand::create(['name' => 'Local Suppliers']);

            while (!feof($file)) {
                $row = fgetcsv($file);

                if ($this->isEmptyRowOrHeaders($row)) continue;

                $rowToInsert = [
                    'name' => $row[0],
                    'brand_id' => $this->getBrandId($row),
                    'barcode' => $row[1],
                    'price' => floatval($row[3]),
                    'image_url' => $row[4],
                    'date_added' => $row[5],
                ];

                DB::table('products')->insert([$rowToInsert]);
            }

            fclose($file);
        });

        return 0;
    }

    /**
     * @return void
     */
    private function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Brand::truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @param $row
     * @return int|string
     */
    private function getBrandId($row)
    {
        $brandName = !empty($row[2]) ? $row[2] : '';

        $brandName = preg_replace('/\s+/', '', $brandName);

        for ($i = 0; $i < count($this->brands); $i++) {
            if ($this->brands[$i] === $brandName) {
                return $i + 1;
            }
        }

        $brand = Brand::create(['name' => $brandName]);

        $this->brands[] = $brandName;

        return $brand->id;
    }

    /**
     * @param $row
     * @return bool
     */
    private function isEmptyRowOrHeaders($row): bool
    {
        return empty($row) || $row[0] === "name";
    }
}
