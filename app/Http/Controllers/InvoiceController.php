<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Order;
use App\User;
use App\Product;
use App\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class InvoiceController extends Controller
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

        $invoices = Cache::remember('invoices', 120, function() {
            return Invoice::whereNotNull('due_at')->with(['products' => function($query) {
                $query->withTrashed();
            },'customer' => function($query) {
                $query->withTrashed();
            }])->get();
        })->splice($start, $limit);

        $count = $invoices->count();

        foreach($invoices as $invoice)
        {
            foreach($invoice->products as $product)
            {
                $product->hideAttributes([
                    'visible', 'category_id', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at',
                    'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
                ]);
                $quantity = $product->pivot->quantity;
                $priceEach = $product->pivot->priceEach;
                $taxPercent = $product->pivot->taxPercent;
                $taxedPriceEach = $this->format_number($priceEach * (100 + $taxPercent) / 100);
                $taxedPriceTotal = $this->format_number($quantity * $taxedPriceEach);
                $totalWithoutTaxes = $this->format_number($priceEach * $quantity);
                $taxAmountTotal = $totalWithoutTaxes* ((100 + $taxPercent) / 100);           
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
        return $invoices;
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
        $invoice = new Invoice();

        $invoice->customer = Customer::findOrFail($request->customer_id);
        $invoice->user = User::findOrFail(1);
        $invoice->entityType = $request->input('entityType', $invoice->customer->type);
        $invoice->company = $request->input('company',  $invoice->customer->company);
        $invoice->name = $request->input('name', $invoice->customer->name);
        $invoice->surname = $request->input('surname', $invoice->customer->surname);
        $invoice->email1 = $request->input('email1', $invoice->customer->email1);
        $invoice->email2 = $request->input('email2', $invoice->customer->email2);
        $invoice->website = $request->input('website', $invoice->customer->website);
        $invoice->phone = $request->input('phone', $invoice->customer->phone);
        $invoice->mobile = $request->input('mobile', $invoice->customer->mobile);
        $invoice->vatid = $request->input('vatid', $invoice->customer->vatid);
        $invoice->taxid = $request->input('taxid', $invoice->customer->taxid);
        $invoice->street1 = $request->input('street1', $invoice->customer->street1);
        $invoice->street2 = $request->input('street2', $invoice->customer->street2);
        $invoice->city = $request->input('city', $invoice->customer->city);
        $invoice->state = $request->input('state', $invoice->customer->state);
        $invoice->zipcode = $request->input('zipcode', $invoice->customer->zipcode);
        $invoice->country = $request->input('country', $invoice->customer->country);
        $invoice->notes = $request->notes;
        $invoice->save();

        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        foreach($request->products as $product)
        {
            $customer = Customer::find($request->get('customer_id'));
            $dbproduct = Product::findOrFail($product['id']);
            if ($customer) {
                $dbproduct->discountPercent = $customer->discountPercent;
            }

            $invoice->addProduct($dbproduct, $product['priceEach'], $product['quantity']);
            $untaxed_total += floatval($product['priceEach']) * floatval($product['quantity']);
            $taxedPriceEach = $product['priceEach'] * (100 + $dbproduct->taxPercent) / 100;
            $taxed_total += round($taxedPriceEach,4) * $product['quantity'];
        }

        $invoice->taxed_total = $taxed_total;
        $invoice->untaxed_total = $untaxed_total;
        $invoice->taxes_total = $taxed_total - $untaxed_total;
        $invoice->save();

        if ($request->get('order_id')) {
            $order = Order::findOrFail($request->get('order_id'));
            $order->update(['completed' => true]);
        }

        $invoice = Invoice::whereNotNull('due_at')->with(['products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($invoice->id);
        foreach($invoice->products as $product)
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

        Cache::forget('invoices');
        return $invoice;
    }

    /**
     * Display the specified resource.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        foreach($invoice->products as $product)
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
        return $invoice;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoice = Invoice::whereNotNull('due_at')->with(['products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($invoice->id);
        $invoice->update($request->all());
        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        $costs_total = 0.00;

        if (count($invoice->products)) {
            $invoice->removeAllProducts();
            foreach($request->products as $newProduct) {
                $product = Product::findOrFail($newProduct['id']);
                $invoice->addProduct($product, $newProduct['priceEach'], $newProduct['quantity']);
                $untaxed_total += floatval($newProduct['priceEach']) * floatval($newProduct['quantity']);
                
                $taxedPriceEach = $newProduct['priceEach'] * (100 + $product->taxPercent) / 100;
                $taxed_total += round($taxedPriceEach,4) * $newProduct['quantity'];   
                $costs_total += $product->basePrice * floatval($newProduct['quantity']);
            }
            $invoice->taxed_total = $taxed_total;
            $invoice->untaxed_total = $untaxed_total;
            $invoice->taxes_total = $taxed_total - $untaxed_total;
            $invoice->costs_total = $costs_total;
            $invoice->update();
        }

        $invoice = Invoice::with(['customer' => function($query) {
            $query->withTrashed();
        }, 'products' => function($query) {
            $query->withTrashed();
        }, 'order' => function($query) {
            $query->withTrashed();
        }])->findOrFail($invoice->id);
        foreach($invoice->products as $product)
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

        Cache::forget('invoices');
        return $invoice;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $order = $invoice->order; //Order::where('invoice_id', $invoice->order_id);
        if ($order) {
            $order->update(['completed' => false]);
            Cache::forget('invoices');
            Cache::forget('orders');
        }
        $invoice->delete();
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
     * Generates a pdf invoice.
     *
     * @param Invoice $invoice
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generatePDF(Invoice $invoice)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($invoice->products as $product) {
            Log::info('renderItem product quantity: '. $product->pivot->quantity );
            $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = round($taxedPriceEach,4);
            $total += $taxedPriceEach * $product->pivot->quantity ;
        }
        $filename = 'invoice-' . $invoice->id . '.pdf';
        return \App\Support\PdfRenderer::createFromView(view('PDF/invoice', [
            'invoice' => $invoice,
            'total' => number_format($total, 4, ',', ''),
        ]), $filename);
    }

    /**
     * Renders an html page with the invoice.
     *
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderItem(Invoice $invoice)
    {
        App::setLocale('fi');
        $total = 0;
        foreach($invoice->products as $product) {

Log::info('renderItem product quantity '. $product->pivot->quantity );

              $taxedPriceEach  =($product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100);
            $taxedPriceEach  = round($taxedPriceEach,4);
            $total += $taxedPriceEach * $product->pivot->quantity ;
        }
        return view('PDF/invoice', [
            'invoice' => $invoice,
            'total' => number_format($total, 4, ',', ''),
        ]);
    }
}
