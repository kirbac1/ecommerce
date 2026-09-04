<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Order;
use App\User;
use App\Product;
use App\Customer;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ReceiptController extends Controller
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

        $receipts = Cache::remember('receipts', 120, function() {
            return Invoice::where('paid', true)->whereNull('due_at')->with(['products' => function($query) {
                $query->withTrashed();
            },'customer' => function($query) {
                $query->withTrashed();
            }])->get();
        })->splice($start, $limit);

        $count = $receipts->count();

        foreach($receipts as $receipt)
        {
            foreach($receipt->products as $product)
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
        }
        return $receipts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //cannot create an invoice if no product is added
        if (!count($request->products)) {
            return;
        }
        $receipt = new Invoice();

        $receipt->customer = Customer::findOrFail($request->customer_id);
        $receipt->user = User::findOrFail(1);
        // These ones belong to the shipping
        $receipt->entityType = $request->input('entityType', $receipt->customer->type);
        $receipt->name = $request->input('name', $receipt->customer->name);
        $receipt->surname = $request->input('surname', $receipt->customer->surname);
        $receipt->company = $request->input('company',  $receipt->customer->company);
        $receipt->email1 = $request->input('email1', $receipt->customer->email1);
        $receipt->email2 = $request->input('email2', $receipt->customer->email2);
        $receipt->website = $request->input('website', $receipt->customer->website);
        $receipt->phone = $request->input('phone', $receipt->customer->phone);
        $receipt->mobile = $request->input('mobile', $receipt->customer->mobile);
        $receipt->vatid = $request->input('vatid', $receipt->customer->vatid);
        $receipt->taxid = $request->input('taxid', $receipt->customer->taxid);
        $receipt->street1 = $request->input('street1', $receipt->customer->street1);
        $receipt->street2 = $request->input('street2', $receipt->customer->street2);
        $receipt->city = $request->input('city', $receipt->customer->city);
        $receipt->state = $request->input('state', $receipt->customer->state);
        $receipt->zipcode = $request->input('zipcode', $receipt->customer->zipcode);
        $receipt->country = $request->input('country', $receipt->customer->country);
        $receipt->notes = $request->notes;
        $receipt->due_at = $request->get('due_at', Carbon::now()->addDays(5));
        $receipt->save();

        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        foreach($request->products as $product)
        {
            $customer = Customer::find($request->get('customer_id'));
            $dbproduct = Product::findOrFail($product['id']);
            if ($customer) {
                $dbproduct->discountPercent = $customer->discountPercent;
            }

            $receipt->addProduct($dbproduct, $product['priceEach'], $product['quantity']);
            $untaxed_total += floatval($product['priceEach'] * $product['quantity']);

            $taxedPriceEach = $product['priceEach'] * (100 + $dbproduct->taxPercent) / 100;
            $taxed_total += round($taxedPriceEach,4) * $product['quantity'];

            
        }

        $receipt->taxed_total = $taxed_total ;
        $receipt->untaxed_total = $untaxed_total;
        $receipt->taxes_total = $taxed_total - $untaxed_total;
        $receipt->save();

        if ($request->get('order_id')) {
            $order = Order::findOrFail($request->get('order_id'));
            $order->update(['completed' => true]);
        }

        $receipt = Invoice::whereNull('due_at')->with(['products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($receipt->id);

        Cache::forget('receipts');
        return $receipt;
    }

    /**
     * Display the specified resource.
     *
     * @param  Invoice  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $receipt)
    {
        foreach($receipt->products as $product)
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
        return $receipt;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Invoice  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $receipt)
    {
        $receipt = Invoice::whereNull('due_at')->where('paid',true)->with(['customer' => function($query) {
            $query->withTrashed();
        },'products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($receipt->id);
        $receipt->update($request->all());
        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        $costs_total = 0.00;

        if (count($request->products)) {
            $receipt->removeAllProducts();
            foreach($request->products as $newProduct) {
                $dbproduct = Product::findOrFail($newProduct['id']);
                $receipt->addProduct(Product::findOrFail($newProduct['id']), floatval($newProduct['priceEach']), floatval($newProduct['quantity']));
                $untaxed_total += floatval($newProduct['priceEach'] * floatval($newProduct['quantity']));
               

                $taxedPriceEach = $newProduct['priceEach'] * (100 + $product->taxPercent) / 100;
                $taxed_total += round($taxedPriceEach,4) * $newProduct['quantity'];   
                
                $costs_total += $dbproduct->basePrice * $newProduct['quantity'];
            }
            $receipt->taxed_total = $taxed_total;
            $receipt->untaxed_total = $untaxed_total;
            $receipt->taxes_total = $taxed_total - $untaxed_total;
            $receipt->costs_total = $costs_total;
            $receipt->update();
        }

        $receipt = Invoice::whereNull('due_at')->with(['customer' => function($query) {
            $query->withTrashed();
        }, 'products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($receipt->id);

        Cache::forget('receipts');
        return $receipt;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $receipt)
    {
        $order = $receipt->order; //Order::where('invoice_id', $receipt->order_id);
        if ($order) {
            $order->update(['completed' => false]);
        }
        Cache::forget('receipts');
        Cache::forget('orders');
        $receipt->delete();
        return response([
            'success' => true
        ], 200);
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
     * Generates a pdf receipt.
     *
     * @param Invoice $receipt
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generatePDF(Invoice $receipt)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($receipt->products as $product) {
             $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = round($taxedPriceEach,4);
            $total += number_format($taxedPriceEach * $product->pivot->quantity,4,',','') ;
        }
        $filename = 'receipt-' . $receipt->id . '.pdf';
        return \App\Support\PdfRenderer::createFromView(view('PDF/receipt', [
            'receipt' => $receipt,
        ]), $filename);
    }

    /**
     * Renders an html page with the receipt.
     *
     * @param Invoice $receipt
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderItem(Invoice $receipt)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($receipt->products as $product) {
            $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = round($taxedPriceEach,4);
            $total += $taxedPriceEach * $product->pivot->quantity;
              
        }
        
        return view('PDF/receipt', [
            'receipt' => $receipt,
            'total' => number_format($total, 4, ',', ''),
        ]);
    }
}
