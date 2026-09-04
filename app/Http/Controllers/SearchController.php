<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Order;
use App\Returned;
use App\Type;
use App\User;
use App\Payment;
use App\Product;
use App\Category;
use App\Customer;
use App\Discount;
use App\Proforma;
use App\Warehouse;
use App\Measureunit;
use App\TicketThread;
use App\Manufacturer;
use App\CustomerGroup;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    /**
     * Retrieves the entities available.
     *
     * @return array
     */
    public function index()
    {
        return [
            'categories',
            'customers',
            'customergroups',
            'discounts',
            'measureunits',
            'payments',
            'products',
            'tickets',
            'types',
            'users',
            'warehouses',
            'orders',
            'invoices',
            'proformas',
            'returns',
        ];
    }

    /**
     * Searches categories.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function categories(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', null);
        $categories =  Category::search($string);

        $count = $categories->count();
        $result = $categories;
        return [
            'count' => $count,
            'result' => $result,
        ];

//        $categories = Cache::remember('categories', 120, function() use ($string) {
//          return Category::search($string);
//        });

//        if ($string != '') {
//            $categories = $categories->filter(function($item) use ($string) {
//                return (stripos($item->name, $string) !== false);
//            });
//        }

        $count = $categories->count();
        if (($start != 0) || ($limit)) {
            $categories = $categories->splice($start, $limit);
        }
        $result = $categories;
        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches customers.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function customers(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $customers = Cache::remember('customers', 120, function() {
            return Customer::orderBy('id','DESC')->with(['group','orders','orders.products','orders.products.manufacturer','orders.products.measureunit','orders.products.measureunit.type','invoices','invoices.products','invoices.products.manufacturer','invoices.products.measureunit','invoices.products.measureunit.type'])->get();
        });

        if ($string != '') {
            $customers = $customers->filter(function($item) use ($string) {
                return (stripos($item->name, $string) !== false) || (stripos($item->company, $string) !== false) || (stripos($item->surname, $string) !== false);
            });
        }
        $count = $customers->count();
        $result = $customers->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches customer groups.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function customergroups(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $customergroups = Cache::remember('customergroups', 120, function() {
            return CustomerGroup::orderBy('id','desc')->get();
        });

        if ($string != '') {
            $customergroups = $customergroups->filter(function($item) use ($string) {
                return (stripos($item->name, $string) !== false) || ($string == '');
            });
        }

        $count = $customergroups->count();
        $result = $customergroups->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches discounts.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function discounts(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $discounts = Cache::remember('discounts', 120, function() {
            return Discount::orderBy('id','desc')->get();
        });

        $count = $discounts->count();
        $result = $discounts->splice($start, $limit);
        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches manufacturers.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function manufacturers(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $manufacturers = Cache::remember('manufacturers', 120, function() {
            return Manufacturer::orderBy('name','ASC')->get();
        });

        if ($string != '') {
            $manufacturers = $manufacturers->filter(function($item) use ($string) {
                return (stripos($item, $string) !== false);
            });
        }

        $count = $manufacturers->count();
        $result = $manufacturers->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches measure units.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function measureunits(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $measureunits = Cache::remember('measureunits', 120, function() {
            return Measureunit::orderBy('name','asc')->get();
        });

        if ($string != '') {
            $measureunits = $measureunits->filter(function($item) use ($string) {
                return stripos($item, $string) !== false;
            });
        }

        $count = $measureunits->count();
        $result = $measureunits->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches payments.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function payments(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $payments = Cache::remember('payments', 120, function() {
            return Payment::orderBy('id','desc')->get();
        });

        if ($string != '') {
            $payments = $payments->filter(function($item) use ($string) {
                return stripos($item, $string) !== false;
            });
        }

        $count = $payments->count();
        $result = $payments->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches products.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function products(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);
        $customer_id = $request->get('customer_id', null);

        $customer = Customer::find($customer_id);
        $manufacturerIds = Manufacturer::like(['name'], "%$string%")->get()->pluck('id')->all();

        // Match the product's own fields OR its brand. These have to be grouped:
        // left ungrouped, SQL binds the AND tighter than the ORs and searching by
        // brand name never returned anything.
        $products = Product::where(function ($query) use ($string, $manufacturerIds) {
                $query->where('name', 'LIKE', "%$string%")
                      ->orWhere('barcode', 'LIKE', "%$string%")
                      ->orWhere('sku', 'LIKE', "%$string%");

                if (count($manufacturerIds)) {
                    $query->orWhereIn('manufacturer_id', $manufacturerIds);
                }
            })
            ->orderBy('name','ASC')
            ->with(['measureunit','measureunit.type','manufacturer']);
        $count = $products->count();
//        $products = $products->splice($start, $limit);
        $products = $products->take($limit)->skip($start)->get();


        $products->transform(function($item) use ($customer) {
            if ($customer) {
                $item->discountPercent = $customer->discountPercent;
            }

            $item->hideAttributes(['packEntryPrice']);
            return $item;
        });

        $result = $products->toArray();
        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches returns.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function returns(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);
        $string = str_replace('#', '', $string);
        $order_id = $request->get('order_id', null);

        $returns = Cache::remember('returned', 120, function() {
            return Returned::orderBy('id','desc')->get();
        });

        if ($string != '') {
            $returns->filter(function($item) use ($string) {
                return stripos($item->rma, $string) !== false;
            });
        }

        if ($order_id) {
            $returns->filter(function($item) use ($order_id) {
                return $item->order_id == $order_id;
            });
        }

        $count = $returns->count();
        $result = $returns->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches tickets.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function tickets(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $tickets = Cache::remember('tickets', 120, function() {
            return TicketThread::with(['user', 'messages'])->orderBy('id','DESC')->get();
        });

        if ($string != '') {
            $tickets = $tickets->filter(function($item) use ($string) {
                return (stripos($item->code, $string) !== false) || (stripos($item->subject, $string) !== false);
            });
        }

        $count = $tickets->count();
        $result = $tickets->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches variable types.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function types(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $types = Cache::remember('types', 120, function() {
            return Type::orderBy('name','ASC')->get();
        });

        if ($string != '') {
            $types = $types->filter(function($item) use ($string) {
                return stristr($item->name, $string) !== false;
            });
        }

        $count = $types->count();
        $result = $types->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches users.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function users(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $users = Cache::remember('users', 120, function() {
            return User::orderBy('name','ASC')->get();
        });

        if ($string != '') {
            $users = $users->filter(function($item) use ($string) {
                return (stristr($item->name, $string) !== false) || (stristr($item->surname, $string) !== false) || (stristr($item->email, $string) !== false);
            });
        }

        $count = $users->count();
        $result = $users->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches proformas.
     *
     * @param Request $request
     * @param string $string
     * @return array
     */
    public function proformas(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $proformas = Cache::remember('proformas', 120, function() {
            return Proforma::with(['customer' => function($query) {
                $query->withTrashed();
            }, 'products' => function($query) {
                $query->withTrashed();
            }, 'products.manufacturer', 'products.measureunit'])->orderBy('id', 'DESC')->get();
        });

        if ($string != '') {
            // If there's a search to be done, do it.
            $proformas = $proformas->filter(function ($item) use ($string) {
                return $item->id == str_replace('#', '', $string);
            });
        }

        $count = $proformas->count();
        $result = $proformas->splice($start, $limit);

        $result->map(function($proforma, $proforma_key) {
            $proforma->products->map(function($product, $product_key) {
                if ($product_key == 'pivot') {
                    $product->setHidden(['pivot']);
                    $product['details'] = $product['pivot'];
                }
                return $product;
            });
            return $proforma;
        });
        return [
            'result' => $result,
            'count' => $count
        ];

    }

    /**
     * Searches orders.
     *
     * @param Request $request
     * @param string $string
     * @return array
     */
    public function orders(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $orders = Cache::remember('orders', 120, function() {
            return Order::with(['customer' => function($query) {
                $query->withTrashed();
            }, 'products' => function($query) {
                $query->withTrashed();
            }, 'products.manufacturer','products.measureunit','products.measureunit.type','proforma', 'proforma.products', 'proforma.products.manufacturer', 'proforma.products.measureunit', 'proforma.products.measureunit.type', 'invoice', 'invoice.products', 'invoice.products.manufacturer', 'invoice.products.measureunit', 'invoice.products.measureunit.type', 'returns', 'returns.products', 'returns.products.manufacturer', 'returns.products.measureunit', 'returns.products.measureunit.type'])->orderBy('id','DESC')->get();
        });

        if ($string != '') {
            // If there's a search to be done, do it.
            $orders = $orders->filter(function ($item) use ($string) {
                return $item->id == str_replace('#', '', $string);
            });
        }

        $count = $orders->count();
        $result = $orders->splice($start, $limit);

        $result->map(function($order, $order_key) {
            $order->products->map(function($product, $product_key) {
                if ($product_key == 'pivot') {
                    $product->setHidden(['pivot']);
                    $product['details'] = $product['pivot'];
                }
                return $product;
            });
            return $order;
        });
        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches invoices.
     *
     * @param Request $request
     * @param string $string
     * @return array
     */
    public function invoices(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $invoices = Cache::remember('invoices', 120, function() {
            return Invoice::with(['customer' => function($query) {
                $query->withTrashed();
            }, 'products' => function($query) {
                $query->withTrashed();
            }, 'products.measureunit', 'products.measureunit.type', 'products.manufacturer', 'order' => function($query) {
                $query->withTrashed();
            }, 'order.products', 'order.products.manufacturer', 'order.products.measureunit', 'order.products.measureunit.type'])->whereNotNull('due_at')->orderBy('id','DESC')->get();
        });

        if ($string != '') {
            // If there's a search to be done, do it.
            $invoices = $invoices->filter(function ($item) use ($string) {
                return $item->id == str_replace('#', '', $string);
            });
        }

        $count = $invoices->count();
        $result = $invoices->splice($start, $limit);

        $result->map(function($invoice, $invoice_key) {
            $invoice->products->map(function($product, $product_key) {
                if ($product_key == 'pivot') {
                    $product->setHidden(['pivot']);
                    $product['details'] = $product['pivot'];
                }
                return $product;
            });
            return $invoice;
        });
        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches receipts.
     *
     * @param Request $request
     * @param string $string
     * @return array
     */
    public function receipts(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $receipts = Cache::remember('receipts', 120, function() {
            return Invoice::with(['customer' => function($query) {
                $query->withTrashed();
            }, 'products' => function($query) {
                $query->withTrashed();
            }, 'products.manufacturer', 'products.measureunit', 'products.measureunit.type', 'order' => function($query) {
                $query->withTrashed();
            }, 'order.products', 'order.products.manufacturer', 'order.products.measureunit', 'order.products.measureunit.type'])->whereNull('due_at')->orderBy('id','DESC')->get();
        });

        if ($string != '') {
            $string = str_replace('#', '', $string);
            // If there's a search to be done, do it.
            $receipts = $receipts->filter(function ($item) use ($string) {
                return $item->id == $string;
            });
        }

        $count = $receipts->count();
        $result = $receipts->splice($start, $limit);

        $result->map(function($receipt, $receipt_key) {
            $receipt->products->map(function($product, $product_key) {
                if ($product_key == 'pivot') {
                    $product->setHidden(['pivot']);
                    $product['details'] = $product['pivot'];
                }
                return $product;
            });
            return $receipt;
        });
        return [
            'result' => $result,
            'count' => $count
        ];
    }

    /**
     * Searches warehouses.
     *
     * @param Request $request
     * @param $string
     * @return mixed
     */
    public function warehouses(Request $request, $string='')
    {
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 40);

        $string = strtolower($string);
        $warehouses = Cache::remember('warehouses', 120, function() {
            return Warehouse::orderBy('name','ASC')->get();
        });
        $warehouses = $warehouses->filter(function($item) use ($string) {
            return stripos($item->name, $string) !== false;
        });

        $count = $warehouses->count();
        $result = $warehouses->splice($start, $limit);

        return [
            'result' => $result,
            'count' => $count
        ];
    }
}
