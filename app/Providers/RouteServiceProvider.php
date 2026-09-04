<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->bind('users', function ($user_id) {
            return \App\User::findOrFail($user_id);
        });
        $router->bind('invoices', function($invoice_id) {
            return \App\Invoice::whereNotNull('invoices.due_at')->with(['products' => function($query) {
                $query->withTrashed();
            },'products.manufacturer','products.measureunit','customer' => function($query) {
                $query->withTrashed();
            },'customer.group'])->findOrFail($invoice_id);
        });
        $router->bind('receipts', function($receipt_id) {
            return \App\Invoice::where('paid',true)->where('due_at',null)->with(['products' => function($query) {
                $query->withTrashed();
            },'products.manufacturer','products.measureunit','customer' => function($query) {
                $query->withTrashed();
            }])->findOrFail($receipt_id);
        });
        $router->bind('orders', function($order_id) {
            return \App\Order::with(['products' => function($query) {
                $query->withTrashed();
            }, 'products.manufacturer', 'products.measureunit', 'customer' => function($query) {
                $query->withTrashed();
            },'invoice', 'receipt', 'returns',])->findOrFail($order_id);
        });
        $router->bind('proformas', function($proforma_id) {
            return \App\Proforma::with(['products' => function($query) {
                $query->withTrashed();
            },'products.manufacturer','products.measureunit','customer' => function($query) {
                $query->withTrashed();
            }, 'customer.group', 'order'])->findOrFail($proforma_id);
        });
        $router->bind('payments', function($payment_id) {
            return \App\Payment::with('method')->findOrFail($payment_id);
        });
        $router->bind('products', function ($product_id) {
            return \App\Product::with(['manufacturer', 'measureunit', 'category' => function($query) {
                $query->select('id', 'parent_id', 'depth', 'name', 'slug');
            }])->findOrFail($product_id);
        });
        $router->bind('returns', function($return_id) {
            return \App\Returned::with(['order' => function($query) {
                $query->withTrashed();
            },'order.products' => function($query) {
                $query->withTrashed();
            },'order.products.manufacturer','order.products.measureunit','products' => function($query) {
                $query->withTrashed();
            }, 'products.manufacturer','products.measureunit', 'customer' => function($query) {
                $query->withTrashed();
            },'customer.group'])->findOrFail($return_id);
        });
        $router->bind('tickets', function($id) {
            return \App\TicketThread::with(['messages', 'messages.user'])->findOrFail($id);
        });
        $router->bind('manufacturer', function($manufacturer_id) {
            return \App\Manufacturer::findOrFail($manufacturer_id);
        });
        $router->bind('discounts', function($discount_id) {
            return \App\Discount::with('product')->findOrFail($discount_id);
        });
        $router->bind('customers', function($customer_id) {
            return \App\Customer::with(['orders', 'group'])->findOrFail($customer_id);
        });
        $router->bind('customergroups', function($customergroup_id) {
            return \App\CustomerGroup::findOrFail($customergroup_id);
        });
        $router->bind('categories', function($category_id) {
            return \App\Category::findOrFail($category_id);
        });

        $router->model('paymentmethods', 'App\PaymentMethod');
        $router->model('tickets.messages', 'App\TicketMessage');
        $router->model('warehouses', 'App\Warehouse');
        $router->model('settings', 'Arcanedev\Settings\Facades\Setting');

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
