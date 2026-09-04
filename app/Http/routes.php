<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// If still in development mode, forget about CORS :)
if (App::isLocal()) {
    header("Access-Control-Allow-Origin: *");
}

// APIS
Route::group([
    'prefix' => '/api/v3',
    'middleware' => 'web'], function () {
    Route::post('/customer/login', 'AuthController@customerLogin');
    Route::get('/customer/logout', 'AuthController@customerLogout');
    Route::post('/customer/register', 'AuthController@customerRegistration');

    Route::resource('/logevents', 'LogEventController');
    Route::resource('/users', 'UserController');
    Route::resource('/customers', 'CustomerController');
    Route::resource('/customergroups', 'CustomerGroupController');
    // Add security check to categories update
    Route::put('/categories/{categories}/move', 'CategoryController@moveCategory');
    Route::post('/categories/addChild', 'CategoryController@addChild');
    Route::resource('/categories', 'CategoryController');
    Route::resource('/discounts', 'DiscountController');

    Route::get('/invoices/{invoices}/generatePDF', 'InvoiceController@generatePDF');
    Route::get('/invoices/{invoices}/renderItem', 'InvoiceController@renderItem');
    Route::resource('/invoices', 'InvoiceController');
    Route::get('/receipts/{receipts}/generatePDF', 'ReceiptController@generatePDF');
    Route::get('/receipts/{receipts}/renderItem', 'ReceiptController@renderItem');
    Route::resource('/receipts', 'ReceiptController');
    Route::resource('/measureunits', 'MeasureunitController');
    Route::get('orders/paid', 'OrderController@getPaidOrders');
    Route::resource('/orders', 'OrderController');
    Route::get('/orders/{orders}/convertToInvoice', 'OrderController@convertToInvoice');
    Route::get('/orders/{orders}/convertToReceipt', 'OrderController@convertToReceipt');
    Route::resource('/paymentmethods', 'PaymentMethodController');
    Route::resource('/payments', 'PaymentController');
    Route::resource('/proformas', 'ProformaController');
    Route::get('/proformas/{proformas}/convertToOrder', 'ProformaController@convertToOrder');
    Route::get('/proformas/{proformas}/generatePDF', 'ProformaController@generatePDF');
    Route::get('/proformas/{proformas}/renderItem', 'ProformaController@renderItem');
    Route::resource('/products', 'ProductController');
    Route::get('/returns/{returns}/generatePDF', 'ReturnedController@generatePDF');
    Route::get('/returns/{returns}/renderItem', 'ReturnedController@renderItem');
    Route::resource('/returns', 'ReturnedController');
    Route::resource('/tickets', 'TicketController');
    Route::resource('/warehouses', 'WarehouseController');
    Route::resource('/manufacturers', 'ManufacturerController');
    Route::post('/imageUpload', 'AdminController@imageUpload');
    Route::resource('/settings', 'SettingController');

    // Search engine
    Route::get('/search/categories/{value?}', 'SearchController@categories');
    Route::get('/search/customers/{value?}', 'SearchController@customers');
    Route::get('/search/customergroups/{value?}', 'SearchController@customergroups');
    Route::get('/search/discounts/{value?}', 'SearchController@discounts');
    Route::get('/search/invoices/{value?}', 'SearchController@invoices');
    Route::get('/search/manufacturers/{value?}', 'SearchController@manufacturers');
    Route::get('/search/measureunits/{value?}', 'SearchController@measureunits');
    Route::get('/search/orders/{value?}', 'SearchController@orders');
    Route::get('/search/payments/{value?}', 'SearchController@payments');
    Route::get('/search/products/{value?}', 'SearchController@products');
    Route::get('/search/proformas/{value?}', 'SearchController@proformas');
    Route::get('/search/receipts/{value?}', 'SearchController@receipts');
    Route::get('/search/returns/{value?}', 'SearchController@returns');
    Route::get('/search/tickets/{value?}', 'SearchController@tickets');
    Route::get('/search/types/{value?}', 'SearchController@types');
    Route::get('/search/users/{value?}', 'SearchController@users');
    Route::get('/search/warehouses/{value?}', 'SearchController@warehouses');
    Route::get('/search', 'SearchController@index');
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

Route::group(['middleware' => ['web']], function() {
    // E-Commerce side (no authentication required yet)
    Route::get('/', 'FrontendController@index');
    Route::get ('/account/login', 'FrontendController@getLogin');
    Route::post('/account/login', 'FrontendController@postLogin');
    Route::get('/account/logout', 'FrontendController@logout');
    Route::get('/about-us', 'FrontendController@aboutus');
    Route::get('/gallery', 'FrontendController@gallery');
    Route::get('/promotions', 'FrontendController@promotions');
    Route::get('/account/register', 'FrontendController@register');
    Route::get('/search', 'FrontendController@search');
    Route::get('/checkout', 'FrontendController@checkout');
    Route::get('/cart', 'FrontendController@cart');
    Route::get('/forgotten', 'FrontendController@forgotten');
    Route::get('/product', 'FrontendController@product');
    Route::get('/brands', 'FrontendController@brands');

    // Cashier side (no authentication required yet)
    Route::get('/cashier', 'CashierController@index');
    Route::get('/cashier/login', 'CashierController@getLogin');
    Route::post('/cashier/login', 'CashierController@postLogin');
    Route::get('/cashier/logout', 'CashierController@logout');

    // Admin panel side (no authentication required yet)
    Route::group(['prefix' => 'admin'], function() {
        Route::get ('/login', 'AdminController@getLogin');
        Route::post('/login', 'AdminController@postLogin');
        Route::get('/logout', 'AdminController@logout');
        Route::get('/', 'AdminController@index');
        Route::get('/dashboard', 'AdminController@dashboard');
    });


    Route::group(['middleware' => 'users'], function () {
        // Cashier side (AUTHENTICATION REQUIRED)
        Route::get('/cashier/index', 'CashierController@index');
        Route::get('/cashier/proforma', 'CashierController@proforma');
        Route::get('/cashier/invoice', 'CashierController@invoice');
        Route::get('/cashier/receipt', 'CashierController@receipt');
        Route::get('/cashier/return', 'CashierController@returned');
        Route::get('/cashier/shipment', 'CashierController@shipment');
        Route::get('/cashier/returnPreview', 'CashierController@returnPreview');
        
    });

    Route::group(['middleware' => 'customers'], function () {
        // E-Commerce side (AUTHENTICATION REQUIRED)
        Route::get('/account', 'FrontendController@account');
        Route::get('/account/wishlist', 'FrontendController@wishlist');
        Route::get('/account/edit', 'FrontendController@accountEdit');
        Route::get('/account/password', 'FrontendController@password');
        Route::get('/account/address_edit', 'FrontendController@addressEdit');
        Route::get('/account/orders', 'FrontendController@orders');
        Route::get('/account/order', 'FrontendController@order');
        Route::get('/account/newsletter', 'FrontendController@newsletter');
    });

    Route::group(['middleware' => 'admins', 'prefix' => 'admin'], function() {
        // Admin side (AUTHENTICATION REQUIRED)
        Route::get('/categories', 'AdminController@categories');
        Route::get('/products', 'AdminController@products');
        Route::get('/products/create', 'AdminController@createProduct');
        Route::get('/products/{products}/edit', 'AdminController@editProduct');
        Route::get('/manufacturers', 'AdminController@manufacturers');
        Route::get('/manufacturers/create', 'AdminController@createManufacturer');
        Route::get('/manufacturers/{manufacturers}/edit', 'AdminController@editManufacturer');
        Route::get('/orders', 'AdminController@orders');
        Route::get('/orders/create', 'AdminController@createOrder');
        Route::get('/orders/{orders}/edit', 'AdminController@editOrder');
        Route::get('/invoices', 'AdminController@invoices');
        Route::get('/invoices/create', 'AdminController@createInvoice');
        Route::get('/invoices/{invoices}/edit', 'AdminController@editInvoice');
        Route::get('/receipts', 'AdminController@receipts');
        Route::get('/receipts/create', 'AdminController@createReceipt');
        Route::get('/receipts/{receipts}/edit', 'AdminController@editReceipt');
        Route::get('/returns', 'AdminController@returns');
        Route::get('/returns/create', 'AdminController@createReturn');
        Route::get('/returns/{returns}/edit', 'AdminController@editReturn');
        Route::get('/proformas', 'AdminController@proformas');
        Route::get('/proformas/create', 'AdminController@createProforma');
        Route::get('/proformas/{proformas}/edit', 'AdminController@editProforma');

        Route::get('/customers', 'AdminController@customers');
        Route::get('/customers/create', 'AdminController@createCustomer');
        Route::get('/customers/{customers}/edit', 'AdminController@editCustomer');
        Route::get('/customergroups', 'AdminController@customergroups');
        Route::get('/customergroups/create', 'AdminController@createCustomergroup');
        Route::get('/customergroups/{customergroups}/edit', 'AdminController@editCustomergroup');
        Route::get('/users', 'AdminController@users');
        Route::get('/users/create', 'AdminController@createUser');
        Route::get('/users/{users}/edit', 'AdminController@editUser');
        Route::get('/support', 'AdminController@tickets');
        Route::get('/support/create', 'AdminController@createTicket');
        Route::get('/support/{tickets}/edit', 'AdminController@editTicket');
        Route::get('/productmigration', 'AdminController@productmigration');
        Route::get('/productmigration/export', 'AdminController@productmigrationExport');
        Route::post('/productmigration/import', 'AdminController@productmigrationImport');
        Route::get('/settings', 'AdminController@settings');
    });
});
