<?php

namespace App\Http\Controllers;

use App\Order;
use App\Proforma;
use App\User;
use App\Product;
use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class ProformaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $start = $request->get('start', 0);

        $proformas = Cache::remember('proformas', 120, function() {
            return Proforma::with(['products' => function($query) {
                $query->withTrashed();
            },'customer' => function($query) {
                $query->withTrashed();
            }])->with(['order'])->get();
        })->splice($start, $limit);

        $count = $proformas->count();

        foreach($proformas as $proforma)
        {
            foreach($proforma->products as $product)
            {
                $product->hideAttributes([
                    'visible', 'category_id', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot', 'order'
                ]);
                $quantity = $product->pivot->quantity;
                $priceEach = $product->pivot->priceEach;
                $taxPercent = $product->pivot->taxPercent;
                $taxedPriceEach = $this->format_number($priceEach * (100 + $taxPercent) / 100);
                $taxedPriceTotal = $this->format_number($quantity * $taxedPriceEach);
                $totalWithoutTaxes = $this->format_number($priceEach * $quantity);
                $taxAmountTotal = $taxedPriceTotal - $totalWithoutTaxes;
            
                $product->details = [
                    'quantity' => $quantity,
                    'priceEach' => $this->format_number($priceEach),
                    'taxPercent' => $this->format_number($taxPercent),
                    'taxAmountEach' => $this->format_number($priceEach * $taxPercent / 100),
                    'taxedPriceEach' =>$taxedPriceEach,                  
                    'taxAmountTotal' => $taxAmountTotal, 
                    'taxedPriceTotal' => $taxedPriceTotal,
                    'totalWithoutTaxes' =>$totalWithoutTaxes,
                ];
            }
        }
        return $proformas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $proforma = new Proforma();

        $customer = Customer::findOrFail($request->customer_id);
        $proforma->customer_id = $request->customer_id;
        $proforma->user_id = Auth::user()->id;
        $proforma->entityType = $request->input('entityType', $customer->type);
        $proforma->name = $request->input('name', $customer->name);
        $proforma->company = $request->input('company', $customer->company);
        $proforma->surname = $request->input('surname', $customer->surname);
        $proforma->email1 = $request->input('email1', $customer->email1);
        $proforma->email2 = $request->input('email2', $customer->email2);
        $proforma->website = $request->input('website', $customer->website);
        $proforma->phone = $request->input('phone', $customer->phone);
        $proforma->mobile = $request->input('mobile', $customer->mobile);
        $proforma->vatid = $request->input('vatid', $customer->vatid);
        $proforma->taxid = $request->input('taxid', $customer->taxid);
        $proforma->street1 = $request->input('street1', $customer->street1);
        $proforma->street2 = $request->input('street2', $customer->street2);
        $proforma->city = $request->input('city', $customer->city);
        $proforma->state = $request->input('state', $customer->state);
        $proforma->zipcode = $request->input('zipcode', $customer->zipcode);
        $proforma->country = $request->input('country', $customer->country);
        $proforma->notes = $request->notes;
        $proforma->save();

        $untaxed_total = 0.0000;
        $taxed_total = 0.0000;
        $costs_total = 0.0000;
        foreach($request->products as $product)
        {
            $customer = Customer::find($request->get('customer_id'));
            $dbproduct = Product::findOrFail($product['id']);
            if ($customer) {
                $dbproduct->discountPercent = $customer->discountPercent;
            }

            $proforma->addProduct($dbproduct, $product['priceEach'], $product['quantity']);
            $untaxed_total += $product['priceEach'] * $product['quantity'];
            $taxedPriceEach = $product['priceEach'] * (100 + $dbproduct->taxPercent) / 100;
            $taxed_total += $taxedPriceEach * $product['quantity'];
            $costs_total += $dbproduct->basePrice * $product['quantity'];
        }

        $proforma->taxed_total = $taxed_total;
        $proforma->untaxed_total = $untaxed_total;
        $proforma->taxes_total = $taxed_total - $untaxed_total;
        $proforma->costs_total = $costs_total;
        $proforma->save();

        $proforma = Proforma::with(['order','products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($proforma->id);
        foreach($proforma->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
            ]);
            $quantity = $product->pivot->quantity;
            $priceEach = $product->pivot->priceEach;
            $taxPercent = $product->pivot->taxPercent;
            $taxedPriceEach = $this->format_number($priceEach * (100 + $taxPercent) / 100);
            $taxedPriceTotal = $this->format_number($quantity * $taxedPriceEach);
            $totalWithoutTaxes = $this->format_number($priceEach * $quantity);
            $taxAmountTotal = $taxedPriceTotal - $totalWithoutTaxes;
            
                $product->details = [
                    'quantity' => $quantity,
                    'priceEach' => $this->format_number($priceEach),
                    'taxPercent' => $this->format_number($taxPercent),
                    'taxAmountEach' => $this->format_number($priceEach * $taxPercent / 100),
                    'taxedPriceEach' =>$taxedPriceEach,                  
                    'taxAmountTotal' => $taxAmountTotal, 
                    'taxedPriceTotal' => $taxedPriceTotal,
                    'totalWithoutTaxes' =>$totalWithoutTaxes,
                ];
        }

        Cache::forget('proformas');
        return $proforma;
    }

    /**
     * Display the specified resource.
     *
     * @param  Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function show(Proforma $proforma)
    {
        foreach($proforma->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
            ]);
            $quantity = $product->pivot->quantity;
            $priceEach = $product->pivot->priceEach;
            $taxPercent = $product->pivot->taxPercent;
            $taxedPriceEach = $this->format_number($priceEach * (100 + $taxPercent) / 100);
            $taxedPriceTotal = $this->format_number($quantity * $taxedPriceEach);
            $totalWithoutTaxes = $this->format_number($priceEach * $quantity);
            $taxAmountTotal = $taxedPriceTotal - $totalWithoutTaxes;

                $product->details = [
                    'quantity' => $quantity,
                    'priceEach' => $this->format_number($priceEach),
                    'taxPercent' => $this->format_number($taxPercent),
                    'taxAmountEach' => $this->format_number($priceEach * $taxPercent / 100),
                    'taxedPriceEach' =>$taxedPriceEach,                  
                    'taxAmountTotal' => $taxAmountTotal, 
                    'taxedPriceTotal' => $taxedPriceTotal,
                    'totalWithoutTaxes' =>$totalWithoutTaxes,
                ];
        }

        return $proforma;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proforma $proforma)
    {
        $proforma = Proforma::with(['products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($proforma->id);
        $proforma->update($request->all());
        $untaxed_total = 0.0000;
        $taxed_total = 0.0000;
        $costs_total = 0.0000;

        Log::info("ProformaController:update product count :" . count($request->products));
        if (count($request->products)) {
            $proforma->removeAllProducts();
            foreach($request->products as $newProduct) {

                $product = Product::findOrFail($newProduct['id']);
                $proforma->addProduct($product, $newProduct['priceEach'], $newProduct['quantity']);
               
                $untaxed_total += $newProduct['priceEach'] * $newProduct['quantity'];
                $taxedPriceEach = $newProduct['priceEach'] * (100 + $product->taxPercent) / 100;
                $taxed_total += $taxedPriceEach * $newProduct['quantity'];          
                $costs_total += $product->basePrice * $newProduct['quantity'];
            }
            $proforma->taxed_total = $taxed_total;
            $proforma->untaxed_total = $untaxed_total;
            $proforma->taxes_total = $taxed_total - $untaxed_total;
            $proforma->costs_total = $costs_total;
            $proforma->update();
        }

        $proforma = Proforma::with(['order','products' => function($query) {
            $query->withTrashed();
        },'customer' => function($query) {
            $query->withTrashed();
        }])->find($proforma->id);
        foreach($proforma->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id','basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
            ]);
            $quantity = $product->pivot->quantity;
            $priceEach = $product->pivot->priceEach;
            $taxPercent = $product->pivot->taxPercent;
            $taxedPriceEach = $this->format_number($priceEach * (100 + $taxPercent) / 100);
            $taxedPriceTotal = $this->format_number($quantity * $taxedPriceEach);
            $totalWithoutTaxes = $this->format_number($priceEach * $quantity);
            $taxAmountTotal = $taxedPriceTotal - $totalWithoutTaxes;       
                $product->details = [
                    'quantity' => $quantity,
                    'priceEach' => $this->format_number($priceEach),
                    'taxPercent' => $this->format_number($taxPercent),
                    'taxAmountEach' => $this->format_number($priceEach * $taxPercent / 100),
                    'taxedPriceEach' =>$taxedPriceEach,                  
                    'taxAmountTotal' => $taxAmountTotal, 
                    'taxedPriceTotal' => $taxedPriceTotal,
                    'totalWithoutTaxes' =>$totalWithoutTaxes,
                ];
        }

        Cache::forget('proformas');
        return $proforma;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Proforma  $proforma
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proforma $proforma)
    {
        $proforma->delete();

        Cache::forget('proformas');
        Cache::forget('orders');

        return response([
            'success' => true
        ], 200);
    }

    /**
     * Convert the proforma to an order.
     *
     * @param Proforma $proforma
     * @return mixed
     */
    public function convertToOrder(Proforma $proforma)
    {
        $order = new Order;
        $order->customer_id = $proforma->customer->id;
        $order->proforma_id = $proforma->id;

        $order->user = User::find(1);
        $order->entityType = $proforma->entityType;
        $order->name = $proforma->name;
        $order->surname = $proforma->surname;
        $order->email1 = $proforma->email1;
        $order->email2 = $proforma->email2;
        $order->website = $proforma->website;
        $order->phone = $proforma->phone;
        $order->mobile = $proforma->mobile;
        $order->vatid = $proforma->vatid;
        $order->taxid = $proforma->taxid;
        $order->street1 = $proforma->street1;
        $order->street2 = $proforma->street2;
        $order->city = $proforma->city;
        $order->state = $proforma->state;
        $order->zipcode = $proforma->zipcode;
        $order->country = $proforma->country;
        $order->notes = $proforma->notes;
        $order->discount = $proforma->discount;
        $order->save();
        $untaxed_total = 0.0000;
        $taxed_total = 0.0000;
        $costs_total = 0.0000;
        foreach($proforma->products as $product) {
            $order->addProduct($product, $product->pivot->priceEach, $product->pivot->quantity, $product->pivot->taxPercent);
            $untaxed_total += $product->pivot->priceEach * $product->pivot->quantity;

            $taxedPriceEach =$product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100;
            $taxed_total += $taxedPriceEach * $product->pivot->quantit;

            $costs_total += $product->basePrice * $product->pivot->quantity;
        }
        $order->taxed_total = $taxed_total;
        $order->untaxed_total = $untaxed_total;
        $order->taxes_total = $taxed_total - $untaxed_total;
        $order->costs_total = $costs_total;
        $order->save();

        Cache::forget('proformas');
        Cache::forget('orders');

        return Order::find($order->id);
    }

    /**
     * Float formatter helper
     *
     * @param $number
     * @param $decimals
     * @return string
     */
    protected function format_number($number, $decimals=4)
    {
        return number_format(round($number, $decimals), $decimals);
    }

    /**
     * Generates a pdf of the proforma.
     *
     * @param Proforma $proforma
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generatePDF(Proforma $proforma)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($proforma->products as $product) {
            $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $total += number_format($taxedPriceEach * $product->pivot->quantity,4,',','') ;
        
        }

        $filename = 'proforma-' . $proforma->id . '.pdf';
        return \App\Support\PdfRenderer::createFromView(view('PDF/proforma', [
            'proforma' => $proforma,
        ]), $filename);
    }

    /**
     * Renders an html page with the proforma.
     *
     * @param Proforma $proforma
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderItem(Proforma $proforma)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($proforma->products as $product) {
            $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = $taxedPriceEach;
            $total += $taxedPriceEach * $product->pivot->quantity ;
              
        }
        return view('PDF/proforma', [
            'proforma' => $proforma,
            'total' => number_format($total, 4, ',', ''),
        ]);
    }
}