<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Ported from the Laravel 5.2 app/Http/routes.php. Everything is here rather
| than split into routes/api.php: the /api/v3 endpoints authenticate with the
| session guard and expect the CSRF token the Vue pages send, so they need the
| web middleware group that this file gets automatically.
|
*/

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LogEventController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MeasureunitController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReturnedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;

// If still in development mode, forget about CORS :)
if (App::isLocal()) {
    header("Access-Control-Allow-Origin: *");
}

// APIS
Route::group(['prefix' => '/api/v3'], function () {
    Route::post('/customer/login', [AuthController::class, 'customerLogin']);
    Route::get('/customer/logout', [AuthController::class, 'customerLogout']);
    Route::post('/customer/register', [AuthController::class, 'customerRegistration']);

    Route::resource('/logevents', LogEventController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/customergroups', CustomerGroupController::class);
    // Add security check to categories update
    Route::put('/categories/{categories}/move', [CategoryController::class, 'moveCategory']);
    Route::post('/categories/addChild', [CategoryController::class, 'addChild']);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/discounts', DiscountController::class);

    Route::get('/invoices/{invoices}/generatePDF', [InvoiceController::class, 'generatePDF']);
    Route::get('/invoices/{invoices}/renderItem', [InvoiceController::class, 'renderItem']);
    Route::resource('/invoices', InvoiceController::class);
    Route::get('/receipts/{receipts}/generatePDF', [ReceiptController::class, 'generatePDF']);
    Route::get('/receipts/{receipts}/renderItem', [ReceiptController::class, 'renderItem']);
    Route::resource('/receipts', ReceiptController::class);
    Route::resource('/measureunits', MeasureunitController::class);
    Route::get('orders/paid', [OrderController::class, 'getPaidOrders']);
    Route::resource('/orders', OrderController::class);
    Route::get('/orders/{orders}/convertToInvoice', [OrderController::class, 'convertToInvoice']);
    Route::get('/orders/{orders}/convertToReceipt', [OrderController::class, 'convertToReceipt']);
    Route::resource('/paymentmethods', PaymentMethodController::class);
    Route::resource('/payments', PaymentController::class);
    Route::resource('/proformas', ProformaController::class);
    Route::get('/proformas/{proformas}/convertToOrder', [ProformaController::class, 'convertToOrder']);
    Route::get('/proformas/{proformas}/generatePDF', [ProformaController::class, 'generatePDF']);
    Route::get('/proformas/{proformas}/renderItem', [ProformaController::class, 'renderItem']);
    Route::resource('/products', ProductController::class);
    Route::get('/returns/{returns}/generatePDF', [ReturnedController::class, 'generatePDF']);
    Route::get('/returns/{returns}/renderItem', [ReturnedController::class, 'renderItem']);
    Route::resource('/returns', ReturnedController::class);
    Route::resource('/tickets', TicketController::class);
    Route::resource('/warehouses', WarehouseController::class);
    Route::resource('/manufacturers', ManufacturerController::class);
    Route::post('/imageUpload', [AdminController::class, 'imageUpload']);
    Route::resource('/settings', SettingController::class);

    // Search engine
    Route::get('/search/categories/{value?}', [SearchController::class, 'categories']);
    Route::get('/search/customers/{value?}', [SearchController::class, 'customers']);
    Route::get('/search/customergroups/{value?}', [SearchController::class, 'customergroups']);
    Route::get('/search/discounts/{value?}', [SearchController::class, 'discounts']);
    Route::get('/search/invoices/{value?}', [SearchController::class, 'invoices']);
    Route::get('/search/manufacturers/{value?}', [SearchController::class, 'manufacturers']);
    Route::get('/search/measureunits/{value?}', [SearchController::class, 'measureunits']);
    Route::get('/search/orders/{value?}', [SearchController::class, 'orders']);
    Route::get('/search/payments/{value?}', [SearchController::class, 'payments']);
    Route::get('/search/products/{value?}', [SearchController::class, 'products']);
    Route::get('/search/proformas/{value?}', [SearchController::class, 'proformas']);
    Route::get('/search/receipts/{value?}', [SearchController::class, 'receipts']);
    Route::get('/search/returns/{value?}', [SearchController::class, 'returns']);
    Route::get('/search/tickets/{value?}', [SearchController::class, 'tickets']);
    Route::get('/search/types/{value?}', [SearchController::class, 'types']);
    Route::get('/search/users/{value?}', [SearchController::class, 'users']);
    Route::get('/search/warehouses/{value?}', [SearchController::class, 'warehouses']);
    Route::get('/search', [SearchController::class, 'index']);
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more. <-------------------- READ THIS, IT IS IMPORTANT!!
|
*/

/**
 * Health check for the deploy pipeline and uptime monitoring.
 *
 * Reports 200 only when the app can boot AND reach its database -- a deploy
 * that serves a styled error page is not a successful deploy.
 */
Route::get('/healthz', function () {
    try {
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'database' => 'unreachable'], 503);
    }

    return response()->json([
        'status' => 'ok',
        'database' => 'ok',
        'app' => config('app.env'),
    ]);
});

Route::group([], function () {
    // E-Commerce side (no authentication required yet)
    Route::get('/', [FrontendController::class, 'index']);
    Route::get ('/account/login', [FrontendController::class, 'getLogin']);
    Route::post('/account/login', [FrontendController::class, 'postLogin']);
    Route::get('/account/logout', [FrontendController::class, 'logout']);
    Route::get('/about-us', [FrontendController::class, 'aboutus']);
    Route::get('/gallery', [FrontendController::class, 'gallery']);
    Route::get('/promotions', [FrontendController::class, 'promotions']);
    Route::get('/account/register', [FrontendController::class, 'register']);
    Route::get('/search', [FrontendController::class, 'search']);
    Route::get('/checkout', [FrontendController::class, 'checkout']);
    Route::get('/cart', [FrontendController::class, 'cart']);
    Route::get('/forgotten', [FrontendController::class, 'forgotten']);
    Route::get('/product', [FrontendController::class, 'product']);
    Route::get('/brands', [FrontendController::class, 'brands']);

    // Cashier side (no authentication required yet)
    Route::get('/cashier', [CashierController::class, 'index']);
    Route::get('/cashier/login', [CashierController::class, 'getLogin']);
    Route::post('/cashier/login', [CashierController::class, 'postLogin']);
    Route::get('/cashier/logout', [CashierController::class, 'logout']);

    // Admin panel side (no authentication required yet)
    Route::group(['prefix' => 'admin'], function() {
        Route::get ('/login', [AdminController::class, 'getLogin']);
        Route::post('/login', [AdminController::class, 'postLogin']);
        Route::get('/logout', [AdminController::class, 'logout']);
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
    });


    Route::group(['middleware' => 'users'], function () {
        // Cashier side (AUTHENTICATION REQUIRED)
        Route::get('/cashier/index', [CashierController::class, 'index']);
        Route::get('/cashier/proforma', [CashierController::class, 'proforma']);
        Route::get('/cashier/invoice', [CashierController::class, 'invoice']);
        Route::get('/cashier/receipt', [CashierController::class, 'receipt']);
        Route::get('/cashier/return', [CashierController::class, 'returned']);
        Route::get('/cashier/shipment', [CashierController::class, 'shipment']);
        Route::get('/cashier/returnPreview', [CashierController::class, 'returnPreview']);
        
    });

    Route::group(['middleware' => 'customers'], function () {
        // E-Commerce side (AUTHENTICATION REQUIRED)
        Route::get('/account', [FrontendController::class, 'account']);
        Route::get('/account/wishlist', [FrontendController::class, 'wishlist']);
        Route::get('/account/edit', [FrontendController::class, 'accountEdit']);
        Route::get('/account/password', [FrontendController::class, 'password']);
        Route::get('/account/address_edit', [FrontendController::class, 'addressEdit']);
        Route::get('/account/orders', [FrontendController::class, 'orders']);
        Route::get('/account/order', [FrontendController::class, 'order']);
        Route::get('/account/newsletter', [FrontendController::class, 'newsletter']);
    });

    Route::group(['middleware' => 'admins', 'prefix' => 'admin'], function() {
        // Admin side (AUTHENTICATION REQUIRED)
        Route::get('/categories', [AdminController::class, 'categories']);
        Route::get('/products', [AdminController::class, 'products']);
        Route::get('/products/create', [AdminController::class, 'createProduct']);
        Route::get('/products/{products}/edit', [AdminController::class, 'editProduct']);
        Route::get('/manufacturers', [AdminController::class, 'manufacturers']);
        Route::get('/manufacturers/create', [AdminController::class, 'createManufacturer']);
        Route::get('/manufacturers/{manufacturers}/edit', [AdminController::class, 'editManufacturer']);
        Route::get('/orders', [AdminController::class, 'orders']);
        Route::get('/orders/create', [AdminController::class, 'createOrder']);
        Route::get('/orders/{orders}/edit', [AdminController::class, 'editOrder']);
        Route::get('/invoices', [AdminController::class, 'invoices']);
        Route::get('/invoices/create', [AdminController::class, 'createInvoice']);
        Route::get('/invoices/{invoices}/edit', [AdminController::class, 'editInvoice']);
        Route::get('/receipts', [AdminController::class, 'receipts']);
        Route::get('/receipts/create', [AdminController::class, 'createReceipt']);
        Route::get('/receipts/{receipts}/edit', [AdminController::class, 'editReceipt']);
        Route::get('/returns', [AdminController::class, 'returns']);
        Route::get('/returns/create', [AdminController::class, 'createReturn']);
        Route::get('/returns/{returns}/edit', [AdminController::class, 'editReturn']);
        Route::get('/proformas', [AdminController::class, 'proformas']);
        Route::get('/proformas/create', [AdminController::class, 'createProforma']);
        Route::get('/proformas/{proformas}/edit', [AdminController::class, 'editProforma']);

        Route::get('/customers', [AdminController::class, 'customers']);
        Route::get('/customers/create', [AdminController::class, 'createCustomer']);
        Route::get('/customers/{customers}/edit', [AdminController::class, 'editCustomer']);
        Route::get('/customergroups', [AdminController::class, 'customergroups']);
        Route::get('/customergroups/create', [AdminController::class, 'createCustomergroup']);
        Route::get('/customergroups/{customergroups}/edit', [AdminController::class, 'editCustomergroup']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/create', [AdminController::class, 'createUser']);
        Route::get('/users/{users}/edit', [AdminController::class, 'editUser']);
        Route::get('/support', [AdminController::class, 'tickets']);
        Route::get('/support/create', [AdminController::class, 'createTicket']);
        Route::get('/support/{tickets}/edit', [AdminController::class, 'editTicket']);
        Route::get('/productmigration', [AdminController::class, 'productmigration']);
        Route::get('/productmigration/export', [AdminController::class, 'productmigrationExport']);
        Route::post('/productmigration/import', [AdminController::class, 'productmigrationImport']);
        Route::get('/settings', [AdminController::class, 'settings']);
    });
});
