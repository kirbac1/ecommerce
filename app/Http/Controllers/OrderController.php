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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
class OrderController extends Controller
{
    public function getPaidOrders(Request $request)
    {
        $limit = $request->get('limit', 10);
        $start = $request->get('start', 0);

        $orders = Cache::remember('orders', 120, function() {
            return Order::with(['products' => function($query) {
                $query->withTrashed();
            },'customer' => function($query) {
                $query->withTrashed();
            }])->with(['invoice' => function($query) {
                $query->where('paid', true);
            }])->with(['receipt' => function($query) {
                $query->where('paid', true);
            }])->get();
        })->splice($start, $limit);

        $count = $orders->count();

        foreach($orders as $order)
        {
            foreach($order->products as $product)
            {
                $product->hideAttributes([
                    'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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
        return $orders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $orders = Order::with(['products' => function($query) {
            $query->withTrashed();
        },'customer' => function($query) {
            $query->withTrashed();
        }])->take($limit)->get();
        foreach($orders as $order)
        {
            foreach($order->products as $product)
            {
                $product->hideAttributes([
                    'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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

        return $orders;
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
        $order = new Order();

        $order->customer = Customer::findOrFail($request->customer_id);
        $order->user = User::findOrFail(1);
        $order->entityType = $request->input('entityType', $order->customer->type);
        $order->name = $request->input('name', $order->customer->name);
        $order->surname = $request->input('surname', $order->customer->surname);
        $order->email1 = $request->input('email1', $order->customer->email1);
        $order->company = $request->input('company', $order->customer->company);
        $order->email2 = $request->input('email2', $order->customer->email2);
        $order->website = $request->input('website', $order->customer->website);
        $order->phone = $request->input('phone', $order->customer->phone);
        $order->mobile = $request->input('mobile', $order->customer->mobile);
        $order->vatid = $request->input('vatid', $order->customer->vatid);
        $order->taxid = $request->input('taxid', $order->customer->taxid);
        $order->street1 = $request->input('street1', $order->customer->street1);
        $order->street2 = $request->input('street2', $order->customer->street2);
        $order->city = $request->input('city', $order->customer->city);
        $order->state = $request->input('state', $order->customer->state);
        $order->zipcode = $request->input('zipcode', $order->customer->zipcode);
        $order->country = $request->input('country', $order->customer->country);
        $order->notes = $request->notes;
        $order->save();

        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        $costs_total = 0.00;
        foreach($request->products as $product)
        {
            $customer = Customer::find($request->get('customer_id'));
            $dbproduct = Product::findOrFail($product['id']);
            if ($customer) {
                $dbproduct->discountPercent = $customer->discountPercent;
            }

            $order->addProduct($dbproduct, $product['priceEach'], $product['quantity']);
            $untaxed_total += $product['priceEach'] * $product['quantity'];

            $taxedPriceEach = $product['priceEach'] * (100 + $dbproduct->taxPercent) / 100;
            $taxed_total += round($taxedPriceEach,4) * $product['quantity'];


            $costs_total += $dbproduct->basePrice * $product['quantity'];
        }

        $order->taxed_total = $taxed_total;
        $order->untaxed_total = $untaxed_total;
        $order->taxes_total = $taxed_total - $untaxed_total;
        $order->costs_total = $costs_total;
        $order->save();

        $order = Order::with(['products' => function($query) {
            $query->withTrashed();
        },'customer' => function($query) {
            $query->withTrashed();
        }])->findOrFail($order->id);
        foreach($order->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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

        Cache::forget('orders');
        return $order;
    }

    /**
     * Display the specified resource.
     *
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        foreach($order->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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

        foreach($order->returns as $return)
        {
            foreach($return->products as $product) {
                $product->hideAttributes([
                    'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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
                ];              }
        }
        return $order;
    }

    /**
     * Convert the order to an invoice.
     *
     * @param Order $order
     * @return mixed
     */
    public function convertToInvoice(Order $order)
    {
        //cannot create an invoice if no product is added
        if (!count($order->products)) {
            return;
        }
        $invoice = new Invoice;
        $invoice->customer_id = $order->customer_id;
        $invoice->due_at = Carbon::now()->addDays(5);
        $invoice->paid = false;
        $invoice->order_id = $order->id;

        $invoice->user = User::find(1);
        $invoice->entityType = $order->entityType;
        $invoice->name = $order->name;
        $invoice->company = $order->company;
        $invoice->surname = $order->surname;
        $invoice->email1 = $order->email1;
        $invoice->email2 = $order->email2;
        $invoice->website = $order->website;
        $invoice->phone = $order->phone;
        $invoice->mobile = $order->mobile;
        $invoice->vatid = $order->vatid;
        $invoice->taxid = $order->taxid;
        $invoice->street1 = $order->street1;
        $invoice->street2 = $order->street2;
        $invoice->city = $order->city;
        $invoice->state = $order->state;
        $invoice->zipcode = $order->zipcode;
        $invoice->country = $order->country;
        $invoice->notes = $order->notes;
        $invoice->discount = $order->discount;
        $invoice->save();

        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        $costs_total = 0.00;
        foreach($order->products as $product) {
            $invoice->addProduct($product, $product->pivot->priceEach, $product->pivot->quantity, $product->pivot->taxPercent);
            $untaxed_total += $product->pivot->priceEach * $product->pivot->quantity;

            $taxedPriceEach =  $product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100;
            $taxed_total += $product->pivot->quantity * round($taxedPriceEach,4);

            $costs_total += $product->pivot->basePrice * $product->pivot->quantity;
        }
        $invoice->taxed_total = $taxed_total;
        $invoice->untaxed_total = $untaxed_total;
        $invoice->taxes_total = $taxed_total - $untaxed_total;
        $invoice->costs_total = $costs_total;
        $invoice->save();
        $invoice = Invoice::whereNotNull('due_at')->with(['products' => function($query) {
            $query->withTrashed();
        },'customer' => function($query) {
            $query->withTrashed();
        },'order'])->findOrFail($invoice->id);
        foreach($invoice->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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
        Cache::forget('orders');
        return $invoice;
    }

    /**
     * Convert the proforma to an invoice.
     *
     * @param Order $order
     * @return mixed
     */
    public function convertToReceipt(Order $order)
    {   
        //cannot create an invoice if no product is added
        if (!count($order->products)) {
            return;
        }
        $receipt = new Invoice;
        $receipt->customer_id = $order->customer_id;
        $receipt->order_id = $order->id;
        $receipt->due_at = null;
        $receipt->paid = true;

        $receipt->user = User::find(1);
        $receipt->entityType = $order->entityType;
        $receipt->name = $order->name;
        $receipt->company = $order->company;
        $receipt->surname = $order->surname;
        $receipt->email1 = $order->email1;
        $receipt->email2 = $order->email2;
        $receipt->website = $order->website;
        $receipt->phone = $order->phone;
        $receipt->mobile = $order->mobile;
        $receipt->vatid = $order->vatid;
        $receipt->taxid = $order->taxid;
        $receipt->street1 = $order->street1;
        $receipt->street2 = $order->street2;
        $receipt->city = $order->city;
        $receipt->state = $order->state;
        $receipt->zipcode = $order->zipcode;
        $receipt->country = $order->country;
        $receipt->notes = $order->notes;
        $receipt->discount = $order->discount;
        $receipt->save();

        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        $costs_total = 0.00;
        foreach($order->products as $product) {
            $receipt->addProduct($product, $product->pivot->priceEach, $product->pivot->quantity, $product->pivot->taxPercent);
            $untaxed_total += $product->pivot->priceEach * $product->pivot->quantity;

            $taxedPriceEach =  $product->pivot->priceEach * (100 + $product->pivot->taxPercent) / 100;
            
            $taxed_total += $product->pivot->quantity * round($taxedPriceEach,4) ;

            $costs_total += $product->pivot->basePrice * $product->pivot->quantity;
        }
        $receipt->taxed_total = $taxed_total;
        $receipt->untaxed_total = $untaxed_total;
        $receipt->taxes_total = $taxed_total - $untaxed_total;
        $receipt->costs_total = $costs_total;
        $receipt->save();
        $receipt = Invoice::whereNull('due_at')->with(['products' => function($query) {
            $query->withTrashed();
        },'customer' => function($query) {
            $query->withTrashed();
        },'order'])->findOrFail($receipt->id);

        Cache::forget('receipts');
        Cache::forget('orders');
        return $receipt;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order = Order::with(['products' => function($query) {
            $query->withTrashed();
        }])->findOrFail($order->id);
        $order->update($request->all());
        $untaxed_total = 0.00;
        $taxed_total = 0.00;
        $costs_total = 0.00;

        Log::info("OrderController:update product count :" . count($request->products));
        if (count($request->products)) {
            $order->removeAllProducts();
            foreach($request->products as $newProduct) {
                $product = Product::findOrFail($newProduct['id']);
                $order->addProduct($product, $newProduct['priceEach'], floatval($newProduct['quantity']));
                $untaxed_total += floatval($newProduct['priceEach']) * floatval($newProduct['quantity']);

                $taxedPriceEach = $newProduct['priceEach'] * (100 + $product->taxPercent) / 100;
                $taxed_total += $taxedPriceEach * floatval($newProduct['quantity']) ;
                $costs_total += $product->basePrice * floatval($newProduct['quantity']);
            }
            $order->taxed_total = $taxed_total;
            $order->untaxed_total = $untaxed_total;
            $order->taxes_total = $taxed_total - $untaxed_total;
            $order->costs_total = $costs_total;
            $order->update();
        }

        $order = Order::with(['products' => function($query) {
            $query->withTrashed();
        }, 'invoice', 'receipt', 'proforma', 'customer' => function($query) {
            $query->withTrashed();
        }])->findOrFail($order->id);
        foreach($order->products as $product)
        {
            $product->hideAttributes([
                'visible', 'category_id', 'qtyPerPack', 'basePrice', 'taxPercent', 'qtyUnit', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'priceEach', 'pivot',
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

        Cache::forget('orders');
        return $order;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        Cache::forget('orders');
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
}
