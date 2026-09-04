<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Route model bindings.
 *
 * Laravel 11 moved routing configuration into bootstrap/app.php, so this no
 * longer extends the framework's RouteServiceProvider or maps route files --
 * it exists purely for these bindings. They matter: each one eager-loads the
 * relations the API responses depend on, and several deliberately include
 * soft-deleted products so historical invoices still render their line items.
 */
class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::bind('users', function ($user_id) {
        return \App\User::findOrFail($user_id);
        });
        Route::bind('invoices', function($invoice_id) {
        return \App\Invoice::whereNotNull('invoices.due_at')->with(['products' => function($query) {
        $query->withTrashed();
        },'products.manufacturer','products.measureunit','customer' => function($query) {
        $query->withTrashed();
        },'customer.group'])->findOrFail($invoice_id);
        });
        Route::bind('receipts', function($receipt_id) {
        return \App\Invoice::where('paid',true)->where('due_at',null)->with(['products' => function($query) {
        $query->withTrashed();
        },'products.manufacturer','products.measureunit','customer' => function($query) {
        $query->withTrashed();
        }])->findOrFail($receipt_id);
        });
        Route::bind('orders', function($order_id) {
        return \App\Order::with(['products' => function($query) {
        $query->withTrashed();
        }, 'products.manufacturer', 'products.measureunit', 'customer' => function($query) {
        $query->withTrashed();
        },'invoice', 'receipt', 'returns',])->findOrFail($order_id);
        });
        Route::bind('proformas', function($proforma_id) {
        return \App\Proforma::with(['products' => function($query) {
        $query->withTrashed();
        },'products.manufacturer','products.measureunit','customer' => function($query) {
        $query->withTrashed();
        }, 'customer.group', 'order'])->findOrFail($proforma_id);
        });
        Route::bind('payments', function($payment_id) {
        return \App\Payment::with('method')->findOrFail($payment_id);
        });
        Route::bind('products', function ($product_id) {
        return \App\Product::with(['manufacturer', 'measureunit', 'category' => function($query) {
        $query->select('id', 'parent_id', 'depth', 'name', 'slug');
        }])->findOrFail($product_id);
        });
        Route::bind('returns', function($return_id) {
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
        Route::bind('tickets', function($id) {
        return \App\TicketThread::with(['messages', 'messages.user'])->findOrFail($id);
        });
        Route::bind('manufacturer', function($manufacturer_id) {
        return \App\Manufacturer::findOrFail($manufacturer_id);
        });
        Route::bind('discounts', function($discount_id) {
        return \App\Discount::with('product')->findOrFail($discount_id);
        });
        Route::bind('customers', function($customer_id) {
        return \App\Customer::with(['orders', 'group'])->findOrFail($customer_id);
        });
        Route::bind('customergroups', function($customergroup_id) {
        return \App\CustomerGroup::findOrFail($customergroup_id);
        });
        Route::bind('categories', function($category_id) {
        return \App\Category::findOrFail($category_id);
        });

        Route::model('paymentmethods', 'App\PaymentMethod');
        Route::model('tickets.messages', 'App\TicketMessage');
        Route::model('warehouses', 'App\Warehouse');
    }
}
