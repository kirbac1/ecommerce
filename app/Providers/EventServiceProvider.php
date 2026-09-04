<?php

namespace App\Providers;

use App\Category;
use App\CustomerGroup;
use App\Invoice;
use App\Manufacturer;
use App\Newsletter;
use App\Order;
use App\Payment;
use App\Product;
use App\Proforma;
use App\Type;
use App\User;
use App\Customer;
use App\Returned;
use App\TicketThread;
use App\Warehouse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        // Automatic hashing of passwords for both users and customers
        User::saving(function(User $user)
        {
            if ($user->isDirty('password') && ($user->password !== '') && ($user->password !== null)) {
                $user->password = Hash::make($user->password);
            }
        });
        Customer::saving(function(Customer $customer)
        {
            if ($customer->isDirty('password') && ($customer->password !== '') && ($customer->password !== null)) {
                $customer->password = Hash::make($customer->password);
            }
        });

        TicketThread::creating(function(TicketThread $ticketThread)
        {
            $ticketThread->code = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 7)), 0, 7);
        });

        Returned::creating(function(Returned $return)
        {
            $return->rma = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5);
        });

        // Invalidate cache on save.
        Category::saved(function(Category $category)
        {
            Cache::forget('categories');
            \Log::info('Category saved. Flushing cache.');
        });
        Category::deleted(function(Category $category)
        {
            Cache::forget('categories');
            \Log::info('Category deleted. Flushing cache.');
        });
        Customer::saved(function(Customer $customer)
        {
            Cache::forget('customers');
            \Log::info('Customer saved. Flushing cache.');
        });
        Customer::deleted(function(Customer $customer)
        {
            Cache::forget('customers');
            \Log::info('Customer deleted. Flushing cache.');
        });
        CustomerGroup::saved(function(CustomerGroup $cg)
        {
            Cache::forget('customers');
            Cache::forget('customergroups');
            \Log::info('CustomerGroup saved. Flushing cache.');
        });
        CustomerGroup::deleted(function(CustomerGroup $cg)
        {
            Cache::forget('customers');
            Cache::forget('customergroups');
            \Log::info('CustomerGroup deleted. Flushing cache.');
        });
        Invoice::saved(function(Invoice $invoice)
        {
            Cache::forget('invoices');
            \Log::info('Invoice saved. Flushing cache.');
        });
        Invoice::deleted(function(Invoice $invoice)
        {
            Cache::forget('invoices');
            \Log::info('Invoice deleted. Flushing cache.');
        });
        Manufacturer::saved(function(Manufacturer $manufacturer)
        {
            Cache::forget('manufacturers');
            \Log::info('Manufacturer saved. Flushing cache.');
        });
        Manufacturer::deleted(function(Manufacturer $manufacturer)
        {
            Cache::forget('manufacturers');
            \Log::info('Manufacturer deleted. Flushing cache.');
        });
        Newsletter::saved(function(Newsletter $newsletter)
        {
            Cache::forget('newsletters');
            \Log::info('Newsletter saved. Flushing cache.');
        });
        Newsletter::deleted(function(Newsletter $newsletter)
        {
            Cache::forget('newsletters');
            \Log::info('Newsletter deleted. Flushing cache.');
        });
        Order::saved(function(Order $order)
        {
            Cache::forget('orders');
            \Log::info('Order saved. Flushing cache.');
        });
        Order::deleted(function(Order $order)
        {
            Cache::forget('orders');
            \Log::info('Order deleted. Flushing cache.');
        });
        Payment::saved(function(Payment $payment)
        {
            Cache::forget('payments');
            \Log::info('Payment saved. Flushing cache.');
        });
        Payment::deleted(function(Payment $payment)
        {
            Cache::forget('payments');
            \Log::info('Payment deleted. Flushing cache.');
        });
        Product::saved(function(Product $product)
        {
            Cache::forget('products');
            \Log::info('Product saved. Flushing cache.');
        });
        Product::deleted(function(Product $product)
        {
            Cache::forget('products');
            \Log::info('Product deleted. Flushing cache.');
        });
        Proforma::saved(function(Proforma $proforma)
        {
            Cache::forget('proformas');
            \Log::info('Proforma saved. Flushing cache.');
        });
        Proforma::deleted(function(Proforma $proforma)
        {
            Cache::forget('proformas');
            \Log::info('Proforma deleted. Flushing cache.');
        });
        Returned::saved(function(Returned $returned)
        {
            Cache::forget('returned');
            \Log::info('Return saved. Flushing cache.');
        });
        Returned::deleted(function(Returned $returned)
        {
            Cache::forget('returned');
            \Log::info('Return deleted. Flushing cache.');
        });
        TicketThread::saved(function(TicketThread $ticketThread)
        {
            Cache::forget('tickets');
            \Log::info('Ticket saved. Flushing cache.');
        });
        TicketThread::deleted(function(TicketThread $ticketThread)
        {
            Cache::forget('tickets');
            \Log::info('Ticket deleted. Flushing cache.');
        });
        Type::saved(function(Type $type)
        {
            Cache::forget('types');
            \Log::info('Type saved. Flushing cache.');
        });
        Type::deleted(function(Type $type)
        {
            Cache::forget('types');
            \Log::info('Type deleted. Flushing cache.');
        });
        User::saved(function(User $user)
        {
            Cache::forget('users');
            \Log::info('User saved. Flushing cache.');
        });
        User::deleted(function(User $user)
        {
            Cache::forget('users');
            \Log::info('User deleted. Flushing cache.');
        });
        Warehouse::saved(function(Warehouse $warehouse)
        {
            Cache::forget('warehouses');
            \Log::info('Warehouse saved. Flushing cache.');
        });
        Warehouse::deleted(function(Warehouse $warehouse)
        {
            Cache::forget('warehouses');
            \Log::info('Warehouse deleted. Flushing cache.');
        });
    }
}
