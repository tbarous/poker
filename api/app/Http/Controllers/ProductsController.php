<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsStoreRequest;
use App\Jobs\MailFilteredProductList;
use App\Mail\NotifyStaff;
use App\Models\Brand;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Throwable;

class ProductsController extends Controller
{
    private $collection;
    private $maxPrice;
    private $minPrice;
    private $brands;

    public function __construct()
    {
        $this->setupCache();
    }

    /**
     * @param Request $request
     * @return View
     */
    function index(Request $request): View
    {
        $data = $this->filter($request);

        return view('products', [
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    function filter(Request $request): string
    {
        $this->applyFilters($request);

        return json_encode([
            "products" => $this->collection->paginate(15),
            "maxPrice" => $this->maxPrice,
            "minPrice" => $this->minPrice,
            "brands" => $this->brands
        ]);
    }

    /**
     * @return void
     */
    private function setupCache(): void
    {
        $this->brands = Cache::rememberForever('brands', function () {
            return Brand::orderBy('name')->get();
        });

        $this->maxPrice = Cache::rememberForever('maxPrice', function () {
            return Product::max("price");
        });

        $this->minPrice = Cache::rememberForever('minPrice', function () {
            return Product::min("price");
        });

        $this->collection = Cache::rememberForever('collection', function () {
            return Product::with('brand')->get();
        });
    }

    private function setup()
    {
//        $this->brands = Brand::orderBy('name')->get();
//        $this->maxPrice = Product::max("price");
//        $this->minPrice = Product::min("price");
//        $this->collection = Product::with('brand')->get();
    }

    /**
     * @return void
     */
    function refreshCache()
    {
        Cache::flush();

        $this->setupCache();
    }

    /**
     * @param $term
     * @return void
     */
    private function search($term)
    {
        if (empty($term)) return;

        $this->collection = $this->collection->filter(function ($item) use ($term) {
            return stripos($item->name, $term) !== false || stripos($item->barcode, $term) !== false;
        });
    }

    /**
     * @param $by
     * @param $direction
     * @return void
     */
    private function sort($by, $direction)
    {
        if (empty($by)) return;

        if ($by === 'brand') $by = 'brand.name';

        if (empty($direction)) {
            $this->collection = $this->collection->sortBy($by, SORT_NATURAL | SORT_FLAG_CASE)->values();

            return;
        }

        $this->collection = $this->collection->sortByDesc($by, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    /**
     * @param $brand
     * @return void
     */
    private function filterByBrand($brand)
    {
        if (empty($brand)) return;

        $this->collection = $this->collection->filter(function ($item) use ($brand) {
            if (empty($item->brand)) return false;

            return $item->brand->name === $brand;
        });
    }

    /**
     * @param $minPrice
     * @param $maxPrice
     * @return void
     */
    private function filterByPrice($minPrice, $maxPrice)
    {
        $this->collection = $this->collection->whereBetween('price', [$minPrice ?? $this->minPrice, $maxPrice ?? $this->maxPrice]);
    }

    /**
     * @param ProductsStoreRequest $request
     * @return Response
     * @throws Throwable
     */
    public function create(ProductsStoreRequest $request): Response
    {
        $request->validated();

        $product = new Product();

        $localSuppliersId = Brand::where('name', 'Local Suppliers')->first()->id;

        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->brand_id = $request->brand_id ?? $localSuppliersId;
        $product->date_added = Carbon::now()->format("d/m/Y H:m:s");
        $filename = $this->getImageName($request, $product);
        $product->image_url = $filename ? asset('uploads') . '/' . $filename : null;

        $product->saveOrFail();

        $this->storeImage($request, $filename);

        $this->notify($product);

        $this->refreshCache();

        return response(200);
    }

    /**
     * @param ProductsStoreRequest $request
     * @param mixed $id
     * @return Response
     */
    public function edit(ProductsStoreRequest $request, $id): Response
    {
        $request->validated();

        $product = Product::findOrFail($id);

        $localSuppliersId = Brand::where('name', 'Local Suppliers')->first()->id;

        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->price = $request->price;
        $product->brand_id = $request->brand_id ?? $localSuppliersId;

        $filename = $this->getImageName($request, $product);
        $product->image_url = $filename !== null ? asset('uploads') . '/' . $filename : $product->image;

        $product->saveOrFail();

        $this->storeImage($request, $filename);

        $this->notify($product);

        $this->refreshCache();

        return response(200);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $product = Product::findOrFail($id);

        $productToDelete = $product->first();

        $product->delete();

        $this->notify($productToDelete);

        $this->refreshCache();

        return response(200);
    }

    /**
     * @param $request
     * @param $product
     * @return string|null
     */
    private function getImageName($request, $product): ?string
    {
        if (!empty($request->image)) {
            return $product->name . $product->barcode . '.' . $request->image->extension();
        }

        return null;
    }

    /**
     * @param $request
     * @param $filename
     * @return void
     */
    private function storeImage($request, $filename)
    {
        if (!empty($request->image)) {
            $request->image->move(public_path('uploads'), $filename);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function report(Request $request): Response
    {
        $this->applyFilters($request);

        Queue::push(new MailFilteredProductList($this->collection));

        return response(200);
    }

    /**
     * @param $request
     * @return void
     */
    private function applyFilters($request)
    {
        $searchTerm = $request->query("search");
        $sortDirection = $request->query("sortDirection");
        $sortBy = $request->query("sort");
        $brand = $request->query("brand");
        $minPrice = $request->query("minPrice");
        $maxPrice = $request->query("maxPrice");

        $this->search($searchTerm);

        $this->sort($sortBy, $sortDirection);

        $this->filterByBrand($brand);

        $this->filterByPrice($minPrice, $maxPrice);
    }

    /**
     * @param $product
     * @return void
     */
    private function notify($product)
    {
        Mail::to("")->queue(new NotifyStaff($product));
    }
}
