<?php

namespace App\Http\Controllers;

use Auth;
use \Log;
use App\Product;
use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        define("MaxLimit", 100);
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 20);
        $limit = $limit > MaxLimit ? MaxLimit : $limit;

        $customer = Customer::find($request->get('customer_id'));

        $products = Cache::remember('products', 120, function() {
            return Product::with('manufacturer', 'measureunit')->with(['category' => function($query) {
                $query->select('id', 'parent_id', 'depth', 'name', 'slug');
            }])->get();
        });

        $count = $products->count();
        // $result = $products->skip($start)->take($limit);
        $result = $products->splice($start, $limit);

        $result->transform(function($item) use ($customer, $user) {
            if ($customer) {
                $item->discountPercent = $customer->discountPercent;
            }

            if (!$user || (!$user->isAdmin)) {
                $item->hideAttributes(['basePrice']);
            }
            return $item;
        });

        return [
            'count' => $count,
            'result' => $result
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $catalog_path = config('catalog_path', '/catalog');
        $product = new Product($request->all()); //::create($request->all());
        $product->save();

        // If we have a product image that doesn't match the standard filename, let's just fix it.
        $current_filepath = $request->get('image', null);
        if ($current_filepath) {
            $id = $product->id;
            $image_fileinfo = pathinfo($current_filepath);
            $extension = $image_fileinfo['extension'];
            $signature = $request->get('signature', null);
            $path_isvalid = Hash::check($current_filepath . Config::get('key'), $signature);
            if (App::isLocal()) Log::info("About to test for correctness the signature of image at $current_filepath");
            if ($path_isvalid) {
                if (App::isLocal()) Log::info('Signature is OK.');
                $correct_filename = "$id.$extension";
                $correct_fullpath = "$correct_filename";
                if ($correct_fullpath != $current_filepath) {
                    // The location/filename are not correct. Let's fix them.
                    File::move(public_path('catalog/') . $current_filepath, public_path() . "$catalog_path/$correct_fullpath");
                    if (App::isLocal()) Log::info("Product with ID $id had an invalid (temporary?) product image path: $current_filepath. Moved it to $correct_fullpath.");
                    $product->update(['image' => $correct_fullpath]);
                }
            } else {
                Log::warning("Signature for image at $current_filepath is NOT CORRECT. Attack blocked?");
            }
        }

        Cache::forget('products');
        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $user = Auth::user();
        $customer = Customer::find(\Illuminate\Support\Facades\Request::get('customer_id'));

        if ($customer) {
            $product->discountPercent = $customer->discountPercent;
        }

        if (($user == null) || (!$user->isAdmin)) {
            $product->hideAttributes(['basePrice']);
        } else {
//            $product->showsAttributes(['signature']);
        }
         Log::info("product inside ProductController:show: ". $product);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if ($product->image != $request->get('image')) {
            // Update the image
            $current_filepath = $request->get('image', null);
            if ($current_filepath) {
                $catalog_path = config('catalog_path', '/catalog');
                $id = $product->id;
                $image_fileinfo = pathinfo($current_filepath);
                $extension = $image_fileinfo['extension'];
                $signature = $request->get('signature', null);
                $path_isvalid = Hash::check($current_filepath . Config::get('key'), $signature);
                if (App::isLocal()) Log::info("About to test for correctness the signature of image at $current_filepath");
                Log::info("$path_isvalid = Hash::check($current_filepath . Config::get('key') -> " . Config::get('key') . ", $signature);");
                if ($path_isvalid) {
                    if (App::isLocal()) Log::info('Signature is OK.');
                    $correct_filename = "$id.$extension";
                    $correct_fullpath = "$catalog_path/$correct_filename";
                    if ($correct_fullpath != $current_filepath) {
                        // The location/filename are not correct. Let's fix them.
                        File::move(public_path() . $current_filepath, public_path() . $correct_fullpath);
                        if (App::isLocal()) Log::info("Product with ID $id had an invalid (temporary?) product image path: $current_filepath. Moved it to $correct_fullpath.");
                        $product->update(['image' => $correct_fullpath]);
                    }
                } else {
                    Log::warning("Signature for image at $current_filepath is NOT CORRECT. Attack blocked?");
                }
            }
        }
        $product->update($request->all());
        Cache::forget('products');
        $product->save();
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        Cache::forget('products');
        return response([
            'success' => true
        ], 200);
    }
}
