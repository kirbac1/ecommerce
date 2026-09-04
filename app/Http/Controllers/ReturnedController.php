<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Order;
use App\Product;
use App\Returned;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use PhantomPdf\PdfGenerator;

class ReturnedController extends Controller
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

        $order_id = $request->get('order_id', null);
        if ($order_id) {
            return Returned::with(['order' => function($query) {
                $query->withTrashed();
            },'products' => function($query) {
                $query->withTrashed();
            }])->where('order_id', $order_id)->take($limit)->get();
        } else {
            return Cache::remember('returned', 120, function() {
                return Returned::with(['order' => function($query) {
                    $query->withTrashed();
                },'products' => function($query) {
                    $query->withTrashed();
                }])->get();
            })->splice($start, $limit);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $returned = new Returned($request->all());
        $order = Order::with(['products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($request->get('order_id'));
        $returned->user = User::findOrFail(1);
        $returned->entityType = $order->entityType;
        $returned->customer_id = $order->customer_id;
        $returned->name = $order->name;
        $returned->company = $order->company;
        $returned->surname = $order->surname;
        $returned->email1 = $order->email1;
        $returned->email2 = $order->email2;
        $returned->website = $order->website;
        $returned->phone = $order->phone;
        $returned->mobile = $order->mobile;
        $returned->vatid = $order->vatid;
        $returned->taxid = $order->taxid;
        $returned->street1 = $order->street1;
        $returned->street2 = $order->street2;
        $returned->city = $order->city;
        $returned->state = $order->state;
        $returned->zipcode = $order->zipcode;
        $returned->country = $order->country;
//        $returned->notes = $request->notes;

        $returned->save();
        foreach($order->products as $ownedProduct) {
            foreach($request->get('products') as $returnedProduct) {
                if ($returnedProduct['quantity'] > 0) {
                    if ($returnedProduct['id'] === $ownedProduct->id) {
                        $returned->products()->attach($ownedProduct->id, [
                            'quantity' => $returnedProduct['quantity'],
                            'priceEach' => $ownedProduct->pivot->priceEach,
                            'taxPercent' => $ownedProduct->pivot->taxPercent,
                        ]);
                        break;
                    }
                }
            }
        }
        $returned = Returned::with(['products' => function($query) {
            $query->withTrashed();
        }])->find($returned->id);
        foreach($returned->products as $product)
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

        Cache::forget('returns');
        return $returned;
    }

    /**
     * Display the specified resource.
     *
     * @param  Returned  $returned
     * @return \Illuminate\Http\Response
     */
    public function show(Returned $returned)
    {
//        foreach($returned->products as $product)
//        {
//            $product->hideAttributes([
//                'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
//            ]);
//            $quantity = $product->pivot->quantity;
//            $priceEach = $product->pivot->priceEach;
//            $taxPercent = $product->pivot->taxPercent;
//            $product->details = [
//                'quantity' => $quantity,
//                'priceEach' => $this->format_number($priceEach),
//                'taxPercent' => $this->format_number($taxPercent),
//                'taxAmountEach' => $this->format_number($priceEach * $taxPercent / 100),
//                'taxedPriceEach' => $this->format_number($priceEach * (100 + $taxPercent) / 100),
//                'totalWithoutTaxes' => $this->format_number($priceEach * $quantity),
//                'taxAmountTotal' => $this->format_number($priceEach * $taxPercent * $quantity / 100),
//                'taxedPriceTotal' => $this->format_number($priceEach * $quantity * (100 + $taxPercent) / 100),
//            ];
//        }
        return $returned;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Returned  $returned
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Returned $returned)
    {
        $returned->update($request->all());
        $request->save();
//        foreach($returned->products as $product)
//        {
//            $product->hideAttributes([
//                'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
//            ]);
//            $quantity = $product->pivot->quantity;
//            $priceEach = $product->pivot->priceEach;
//            $taxPercent = $product->pivot->taxPercent;
//            $product->details = [
//                'quantity' => $quantity,
//                'priceEach' => $this->format_number($priceEach),
//                'taxPercent' => $this->format_number($taxPercent),
//                'taxAmountEach' => $this->format_number($priceEach * $taxPercent / 100),
//                'taxedPriceEach' => $this->format_number($priceEach * (100 + $taxPercent) / 100),
//                'totalWithoutTaxes' => $this->format_number($priceEach * $quantity),
//                'taxAmountTotal' => $this->format_number($priceEach * $taxPercent * $quantity / 100),
//                'taxedPriceTotal' => $this->format_number($priceEach * $quantity * (100 + $taxPercent) / 100),
//            ];
//        }

        Cache::forget('returns');
        return $returned;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Returned  $returned
     * @return \Illuminate\Http\Response
     */
    public function destroy(Returned $returned)
    {
        $returned->delete();
        Cache::forget('returns');
        return response([
            'success' => true
        ], 200);
    }

    /**
     * Generates a pdf return receipt.
     *
     * @param Returned $returned
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generatePDF(Returned $returned)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($returned->products as $product) {
            $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = round($taxedPriceEach,4);
            $total += $taxedPriceEach * $product->pivot->quantity ;
        }
        $filename = 'return-' . $returned->id . '.pdf';
        return \App\Support\PdfRenderer::createFromView(view('PDF/return', [
            'return' => $returned,
            'total' => number_format($total, 4, ',', ''),
        ]), $filename);
    }

    /**
     * Renders an html page with the return receipt.
     *
     * @param Returned $returned
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderItem(Returned $return)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($return->products as $product) {
             
            $taxedPriceEach  = ($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = round($taxedPriceEach,4);
            $total += $taxedPriceEach * $product->pivot->quantity ;
        }
        return view('PDF/return', [
            'return' => $return,
            'total' => number_format($total, 4, ',', ''),
        ]);
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
}
