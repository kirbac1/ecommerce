<?php

namespace App\Http\Controllers;

use App;
use Auth;
use App\User;
use App\Order;
use \DB as DB;
use App\Product;
use App\Invoice;
use App\Proforma;
use App\Category;
use App\Customer;
use App\Returned;
use Carbon\Carbon;
use App\Measureunit;
use App\TicketThread;
use App\Manufacturer;
use App\CustomerGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Support\Settings as Setting;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    // Only allow admins and superadmins
    public function __construct()
    {
        $this->middleware(['web']);
    }

    public function index()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->isAdmin) return redirect('/admin/dashboard');
            else {
                App::abort(401, 'Unauthorized.');
                return false;
            }
        } else return redirect('/admin/login');
    }

    public function dashboard()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        $returned = Returned::where('created_at', '>', Carbon::now()->subDays(30))->count();
        $sold = Invoice::where('created_at', '>', Carbon::now()->subDays(30))->count();
        $returnedPercent = 0;
        if ($sold > 0) {
            $returnedPercent = round($returned / $sold * 100);
        }

        $paidInvoices = Invoice::whereNotNull('due_at')->where('created_at', '>', Carbon::now()->subDays(30))->where('paid', true)->count();
        $totalInvoices = Invoice::whereNotNull('due_at')->where('created_at', '>', Carbon::now()->subDays(30))->count();
        $paidInvoicesPercent = 100;
        if ($totalInvoices) {
            $paidInvoicesPercent = round($paidInvoices / $totalInvoices * 100);
        }

        $ordersCount = Order::where('created_at', '>', Carbon::now()->subDays(30))->count();
        $completedOrders = Order::where('created_at', '>', Carbon::now()->subDays(30))->where('completed', true)->count();
        if ($ordersCount == 0) $completedOrdersPercent = '100';
        else $completedOrdersPercent = ceil($completedOrders / $ordersCount * 100);

        $sells = Invoice::where('created_at', '>', Carbon::now()->subDays(30))->count();

        /**
         * The saint query of wonders.
         * Basically, it sums all the profits for each item in a proforma, then joins the products inside the proforma
         * to the Products table, and calculates the revenue for each product in that proforma.
         * Then, sums all the gains for every proforma, including untaxed prices, taxed prices and costs, giving out
         * a beautiful result.
         *
        // ->totalUntaxedPrice
        // ->totalTaxedPrice
        // ->totalProfits

        $days = 30;
        $query = DB::select("SELECT SUM(totalUntaxedPrice) as totalUntaxedPrice, SUM(totalTaxedPrice) as totalTaxedPrice, SUM(totalProfits) as totalProfits FROM (SELECT SUM(proforma_product.priceEach * proforma_product.quantity) AS totalUntaxedPrice,  SUM(proforma_product.priceEach * proforma_product.quantity * ( 100 + proforma_product.taxPercent ) /100 ) AS totalTaxedPrice,  SUM((proforma_product.priceEach - products.basePrice) * proforma_product.quantity) AS totalProfits FROM proforma_product JOIN products ON proforma_product.product_id = products.id WHERE proforma_product.created_at > '" . Carbon::now()->subDays($days)->format('Y-m-d') . "' GROUP BY proforma_product.proforma_id) as sums")[0];
        $grossRevenues = 24456799;
        $netRevenues = 47896536;
         */

        $grossRevenues = Invoice::where('created_at', '>', Carbon::now()->subDays(30))->sum('untaxed_total');
        $costs = Invoice::where('created_at', '>', Carbon::now()->subDays(30))->sum('costs_total');
        $netRevenues = $grossRevenues - $costs;

        // Best sellers
        $res = DB::select("SELECT SUM(quantity) AS quantity, product_id AS id FROM invoice_product WHERE created_at > '" . Carbon::now()->subDays(30)->format('Y-m-d') . "' GROUP BY product_id ORDER BY quantity DESC LIMIT 5");
        $bestSellers = [];
        foreach ($res as $bs) {
            $bestSeller = Product::with('manufacturer')->find($bs->id);
            if ($bestSeller) {
                $bestSeller->quantity = $bs->quantity ? $bs->quantity : 0;
                array_push($bestSellers, $bestSeller);
            }
        }

        // Worst sellers
        $res = DB::select("SELECT SUM(quantity) AS quantity, product_id AS id FROM invoice_product WHERE created_at > '" . Carbon::now()->subDays(30)->format('Y-m-d') . "' GROUP BY product_id ORDER BY quantity ASC LIMIT 5");
        $worstSellers = [];
        foreach ($res as $ws) {
            $worstSeller = Product::with('manufacturer')->find($ws->id);
            if ($worstSeller) {
                $worstSeller->quantity = $ws->quantity ? $ws->quantity : 0;
                array_push($worstSellers, $worstSeller);
            }
        }

        // Last 30 days sales: one bucket per day, oldest first.
        $since = Carbon::now()->subDays(30)->format('Y-m-d');
        $res = DB::select("SELECT COUNT(*) AS count, SUM(untaxed_total) AS revenue, DATEDIFF(NOW(), created_at) AS day FROM invoices WHERE created_at > '" . $since . "' AND deleted_at IS NULL GROUP BY day");
        $salesPerDay = array_fill(0, 31, 0);
        $revenuePerDay = array_fill(0, 31, 0);
        foreach ($res as $val) {
            $day = intval($val->day);
            if ($day < 0 || $day > 30) continue;
            $salesPerDay[30 - $day] = intval($val->count);
            $revenuePerDay[30 - $day] = round(floatval($val->revenue));
        }
        $salesDays = implode(',', $salesPerDay);
        $revenueDays = implode(',', $revenuePerDay);

        return view('admin.index', [
            'user' => $user,
            'returnedPercent' => $returnedPercent,
            'paidInvoicesPercent' => $paidInvoicesPercent,
            'completedOrdersPercent' => $completedOrdersPercent,
            'grossRevenues' => number_format($grossRevenues, 0, '.', "'"),
            'netRevenues' => number_format($netRevenues, 0, '.', "'"),
            'sells' => $sells,
            'ordersCount' => $ordersCount,
            'bestSellers' => $bestSellers,
            'worstSellers' => $worstSellers,
            'salesDays' => $salesDays,
            'revenueDays' => $revenueDays,
        ]);
    }

    public function customers()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.customers', [
            'user' => $user,
        ]);
    }

    public function editCustomer(Customer $customer)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.customerEdit', [
            'user' => $user,
            'customer' => $customer,
        ]);
    }

    public function createCustomer(Customer $customer)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.customerEdit', [
            'user' => $user,
            'customer' => null,
        ]);
    }

    public function customergroups()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.customergroups', [
            'user' => $user,
        ]);
    }

    public function createCustomergroup()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.customergroupEdit', [
            'user' => $user,
            'customergroup' => null,
        ]);
    }

    public function editCustomergroup(CustomerGroup $customerGroup) {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.customergroupEdit', [
            'user' => $user,
            'customergroup' => $customerGroup,
        ]);
    }

    public function users()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.users', [
            'user' => $user,
        ]);
    }

    public function createUser()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.userEdit', [
            'user' => $user,
            'editUser' => null,
        ]);
    }

    public function editUser(User $editUser) {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.userEdit', [
            'user' => $user,
            'editUser' => $editUser,
        ]);
    }

    public function products()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.products', [
            'user' => $user,
        ]);
    }

    public function createProduct()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.productEdit', [
            'user' => $user,
            'product' => null,
        ]);
    }

    public function editProduct(Product $product) {
        Log::info("product inside AdminController:editProduct: ".$product);
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.productEdit', [
            'user' => $user,
            'product' => $product,
        ]);
    }

    public function manufacturers()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.manufacturers', [
            'user' => $user,
        ]);
    }

    public function createManufacturer()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.manufacturerEdit', [
            'user' => $user,
            'manufacturer' => null,
        ]);
    }

    public function editManufacturer(Manufacturer $manufacturer) {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.manufacturerEdit', [
            'user' => $user,
            'manufacturer' => $manufacturer,
        ]);
    }

    public function orders()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.orders', [
            'user' => $user,
        ]);
    }

    public function editOrder(Order $order)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.orderEdit', [
            'user' => $user,
            'order' => $order,
        ]);
    }

    public function createOrder()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.orderEdit', [
            'user' => $user,
            'order' => null,
        ]);
    }

    public function returns(Request $request)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.returns', [
            'user' => $user,
            'order_id' => $request->get('order_id', null),
        ]);
    }

    public function editReturn(Request $request, Returned $return)
    {
        $user = Auth::user();
        $order_id = $request->get('order_id', null);
        App::setLocale($user->language);

        return view('admin.sales.returnEdit', [
            'user' => $user,
            'return' => $return,
            'order_id' => $order_id,
        ]);
    }

    public function createReturn(Request $request)
    {
        $user = Auth::user();
        App::setLocale($user->language);
        $order_id = $request->get('order_id', null);

        return view('admin.sales.returnEdit', [
            'user' => $user,
            'return' => null,
            'order_id' => $order_id,
        ]);
    }

    public function tickets()
    {
        $user = Auth::user();

        $tickets = TicketThread::all();
        App::setLocale($user->language);

        return view('admin.support', [
            'user' => $user,
            'tickets' => $tickets
        ]);
    }

    public function editTicket(TicketThread $ticketThread)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.supportEdit', [
            'user' => $user,
            'ticket' => $ticketThread,
            'superAdmin' => $user->superAdmin,
        ]);
    }

    public function createTicket()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.supportCreate', [
            'user' => $user,
            'ticket' => null,
            'superAdmin' => $user->superAdmin,
        ]);
    }

    public function productmigration()
    {
        $user = Auth::user();

        return view('admin.import_export', [
            'user' => $user,
            'superAdmin' => $user->superAdmin,
        ]);
    }

    public function invoices()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.invoices', [
            'user' => $user,
            'superAdmin' => $user->superAdmin,
        ]);
    }

    public function editInvoice(Invoice $invoice)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.invoiceEdit', [
            'user' => $user,
            'invoice' => $invoice,
        ]);
    }

    public function createInvoice()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.invoiceEdit', [
            'user' => $user,
            'invoice' => null,
        ]);
    }

    public function getInvoice(Invoice $invoice)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.invoiceGet', [
            'user' => $user,
            'invoice' => $invoice,
        ]);
    }

    public function receipts()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.receipts', [
            'user' => $user,
            'superAdmin' => $user->superAdmin,
        ]);
    }

    public function editReceipt(Invoice $receipt)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.receiptEdit', [
            'user' => $user,
            'receipt' => $receipt,
        ]);
    }

    public function createReceipt()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.receiptEdit', [
            'user' => $user,
            'receipt' => null,
        ]);
    }

    public function getReceipt(Invoice $receipt)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.receiptGet', [
            'user' => $user,
            'receipt' => $receipt,
        ]);
    }

    public function proformas()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.proformas', [
            'user' => $user,
        ]);
    }

    public function editProforma(Proforma $proforma)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.proformaEdit', [
            'user' => $user,
            'proforma' => $proforma,
        ]);
    }

    public function createProforma()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.proformaEdit', [
            'user' => $user,
            'proforma' => null,
        ]);
    }

    public function getProforma(Proforma $proforma)
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.sales.proformaGet', [
            'user' => $user,
            'proforma' => $proforma,
        ]);
    }

    public function productmigrationExport()
    {
        $products = Product::all()->each(function($row) {
            $row->setHidden(['id', 'manufacturer_id', 'category_id', 'measureunit_id', 'created_at', 'updated_at', 'deleted_at', 'taxAmount', 'taxedPrice', 'signature', 'manufacturer_id']);
        });
        // maatwebsite/excel 2.1 has no PHP 8 release, and this only ever produced
        // a flat CSV, so it is written directly.
        $rows = $products->map(function ($product) {
            return $product->toArray();
        })->all();

        $handle = fopen('php://temp', 'r+');

        if ($rows) {
            fputcsv($handle, array_keys(reset($rows)));
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }

    /**
     * Read a CSV into Fluent rows keyed by lower-cased header.
     *
     * Fluent supports both ->property and ['key'] access, which is what the
     * importer below expects from the rows maatwebsite/excel used to hand it.
     *
     * @return \Illuminate\Support\Fluent[]
     */
    private static function readCsv($filename)
    {
        $handle = fopen($filename, 'r');

        if ($handle === false) {
            return [];
        }

        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);
            return [];
        }

        $headers = array_map(function ($header) {
            return strtolower(trim($header));
        }, $headers);

        $rows = [];

        while (($line = fgetcsv($handle)) !== false) {
            // Skip blank trailing lines rather than importing empty products.
            if ($line === [null] || $line === ['']) {
                continue;
            }

            $line = array_pad(array_slice($line, 0, count($headers)), count($headers), null);
            $rows[] = new Fluent(array_combine($headers, $line));
        }

        fclose($handle);

        return $rows;
    }

    public function productmigrationImport(Request $request)
    {
        Log::info('Running import.');
        $filename = storage_path('/import-' . time() . '.csv');
        file_put_contents($filename, $request->get('file_content'));

        $import = self::readCsv($filename);
        foreach($import as $product) {
            if (($product->manufacturer !== '') && ($product->manufacturer !== null)) {
                $manufacturer_id = Manufacturer::firstOrCreate([ 'name' => $product->manufacturer])->id;
            } else {
                $manufacturer_id = null;
            }

            if (($product->visible == '') || ($product->visible == null) || ($product->visible == 'true')) {
                $product->visible = true;
            } else {
                $product->visible = false;
            }

            $measureunit = Measureunit::where('name', $product->measureunit)->first();
            if (!$measureunit) {
                // If not set, let's assign a "pcs" measure unit.
                $measureunit_id = Measureunit::where('name', 'pcs')->first()->id;
            } else {
                $measureunit_id = $measureunit->id;
            }

            $oldProduct = Product::with(['category','manufacturer','measureunit'])->firstOrNew([
                'sku' => strval($product['sku']),
                'manufacturer_id' => $manufacturer_id,
            ]);

            // If the new product doesn't have a name, and the stored one doesn't exist yet, skip it.
            if ((!$product->name) && (!$oldProduct->name)) {
                \Log::warning("THE PRODUCT IS BEING SKIPPED: IT IS NEW AND DOESN'T HAVE A NAME!");
                continue;
            }
            else $oldProduct->name = $product->name;

            if ($oldProduct->qtyPerPack) {
                // Update it if there's something to update
                $oldProduct->qtyPerPack = $product->qtyperpack ? $product->qtyperpack : $oldProduct->qtyPerPack;
            } else {
                // If the product doesn't exist and qtyPerPack is not set, let's say it's 1.
                $oldProduct->qtyPerPack = $product->qtyperpack ? $product->qtyperpack : 1;
            }

            // Associate it to the manufacturer
            if ($product->manufacturer != '') {
                $manufacturer = Manufacturer::firstOrCreate([
                    'name' => $product->manufacturer,
                ]);
                $oldProduct->manufacturer_id = $manufacturer->id;
            } else {
                $oldProduct->manufacturer_id = null;
            }

            if ($oldProduct->basePrice) {
                // If the product exists and there is an update for the basePrice, update it, otherwise use the old one
                $oldProduct->basePrice = $product->baseprice ? $product->baseprice : $oldProduct->basePrice;
            } else {
                // If the product doesn't exist, use the basePrice provided, or set 0 if there isn't one.
                $oldProduct->basePrice = $product->baseprice ? $product->baseprice : 0;
            }

            $category = Category::where('name', $product->category)->first();
            if ($oldProduct->category_id) {
                // If the product exists and there is an update for the category, update it, otherwise use the old one
                $oldProduct->category_id = $category ? $category->id : $oldProduct->category_id;
            } else {
                // If the product doesn't exist, use the category provided, or set null if there isn't one.
                $oldProduct->category_id = $category ? $category->id : null;
            }

            if ($oldProduct->taxPercent) {
                // If the product exists and there is an update for the taxPercent, update it, otherwise use the old one
                $oldProduct->taxPercent = $product->taxpercent ? $product->taxpercent : $oldProduct->taxPercent;
            } else {
                // If the product doesn't exist, use the taxPercent provided, or set 0 if there isn't one.
                $oldProduct->taxPercent = $product->taxpercent ? $product->taxpercent : 14;
            }

            if ($oldProduct->priceEach) {
                // If the product already exists and had a price set, update it if there is an update, otherwise don't.
                $oldProduct->priceEach = $product->priceeach == null ? $oldProduct->priceEach : floatval($product->priceeach);
            } else {
                // If the product don't exists, set the price if given, otherwise add +30% to the basePrice.
                $oldProduct->priceEach = $product->priceeach == null ? round(floatval($product->baseprice) * (100 + floatval(Setting::get('store_profitpercent', 30))) / 100, 4) : floatval($product->priceeach);
            }

            // Set the measure unit
            $oldProduct->measureunit_id = $measureunit_id;

            if ($product->visible !== null) {
                // If the visibility field is set, use it
                $oldProduct->visible = $product->visible === false ? false : true;
            } else {
                // If the visibility field is not set, use the previous one or set it to true if the product is new.
                $oldProduct->visible = $oldProduct->visible === false ? false : true;
            }

            if (!!$oldProduct->sku) {
                // We have a sku already stored, let's update it if there is one
                $oldProduct->sku = $product->sku ? $product->sku : $oldProduct->sku;
            } else {
                // We don't have a sku stored, let's set it if there is one, otherwise it's null.
                $oldProduct->sku = $product->sku ? $product->sku : null;
            }

            if (!!$oldProduct->barcode) {
                // We have a sku already stored, let's update it if there is one
                $oldProduct->barcode = $product->barcode ? $product->barcode : $oldProduct->barcode;
            } else {
                // We don't have a sku stored, let's set it if there is one, otherwise it's null.
                $oldProduct->barcode = $product->barcode ? $product->barcode : null;
            }
            

            // Imported images can be inside /image_import/
            // Images must be inside /public/catalog/.
            if (($product->image) && ($product->image != '')) { // Basically, if not null.
                \Log::info('Checking if ' . base_path() . '/image_import/' . $product->image . ' exists.');
                if (file_exists(base_path() . '/image_import/' . $product->image)) {
                    \Log::info('It exists.');
                    $oldname = base_path() . '/image_import/' . $product->image;
                    $extension = strtolower(pathinfo($oldname, PATHINFO_EXTENSION));
                    try {
                        if (file_exists(public_path() . '/catalog/' . $oldProduct->image) && ($oldProduct->image != '') && ($oldProduct->image)) {
                            Log::info('Trying to unlink ' . public_path() . '/catalog/' . $oldProduct->image);
                            unlink(public_path() . '/catalog/' . $oldProduct->image);
                            Log::info('Unlinked.');
                        }
                    } catch(\Exception $e) {
                        Log::error('Exception happened: ' . $e);
                    }
                    Log::info('Renaming ' . $oldname . ' to ' . public_path() . '/catalog/' . $oldProduct->id . ".$extension");
                    rename($oldname, public_path() . '/catalog/' . $oldProduct->id . ".$extension");
                    Log::info('renamed.');
                    $oldProduct->image = $oldProduct->id . ".$extension";
                    Log::info('Product image changed:' . $oldProduct->image);
                } elseif (file_exists(public_path() . '/catalog/' . $product->image)) {
                    // Do nothing, the file is where it should be.
                    Log::info('Do nothing, the file is where it should be: ' . public_path() . '/catalog/' . $product->image);

                } elseif (($oldProduct->image != '') && (file_exists(public_path() . '/catalog/' . $oldProduct->image))) {
                    Log::info("The new product don't have a correct image, or I can't find the image told me by the csv. But still, there was an older one, so I will keep it.");
                } elseif ($oldProduct->image == '') {
                    Log::info('The image csv field is empty, and there was no older image. Setting it to null.');
                    $oldProduct->image = null;
                } else {
                    $oldProduct->image = null;
                    Log::info("The product is new and I didn't find any immage inside the image_import/ directory. I'm setting the image NULL.");
                }
                $oldProduct->save();
                Log::info('Updating the product.');
            }

        }
        unlink($filename);

        return ['success' => true];
    }

    public function imageUpload(Request $request)
    {
        $catalog_dir = config('catalog_path', '/catalog');
        $length = 10;
        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $file = $request->file('productImageFile');
        $extension = $file->getClientOriginalExtension();

        // Reject if the extension is invalid.
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($extension), $allowed_extensions)) {
            try {
                unlink($request->file('productImageFile'));
            } catch(\Exception $e) {}
            return [
                'success' => false,
                'path' => null,
                'reason' => 'Forbidden file extension.',
            ];
        }

        $id = $request->get('product_id', null);
        if ($id != 'undefined') {
            Log::info('Product exists. ID: ' . $id);
            // Product exists
            $product = Product::findOrFail($id);
            $dbfile = $product->image;
            if ($dbfile) {
                try {
                    unlink(public_path($dbfile));
                    Log::info('Unlink done to ' . $dbfile);
                } catch(\Exception $e) {
                    Log::info('Exception:' . $e);
                }
            }
            $filename = "$id.$extension";
            $fullpath = "$catalog_dir/$filename";
            try {
                $file->move(public_path($catalog_dir), $filename);
                Log::info('Image moved from ' . $file . ' to ' . public_path($catalog_dir) . '/' . $filename);
                $product->update(['image' => $filename]);
                Log::info('Product updated.');
            } catch(\Exception $e) {
                Log::info('Exception happened: ' . $e);
            }
            $signature = Hash::make($fullpath . Config::get('key'));
            return [
                'success' => true,
                'signature' => $signature,
                'path' => $filename,
            ];
        } else {
            // Product doesn't exist yet
            $stored_filename = "temp_$randomString.$extension";
            $fullpath = "$catalog_dir/$stored_filename";
            try {
                $file->move(public_path('/catalog'), $stored_filename);
                Log::info('File ' . $file . ' moved to ' . public_path('/catalog') . '/' . $stored_filename);
            } catch(\Exception $e) {
                Log::info('Exception happened (3): ' . $e);
            }
            $signature = Hash::make($stored_filename . Config::get('key'));
            return [
                'success' => true,
                'signature' => $signature,
                'path' => $stored_filename,
            ];
        }
    }

    public function getLogin()
    {
        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'enabled' => 1,
        ];
        if (Auth::attempt($credentials)) {
            return [
                'success' => true,
                'redirect' => '/admin/dashboard',
            ];
        } else {
            return [
                'success' => false,
                'redirect' => '/admin/login',
            ];
        }
    }

    public function logout()
    {
        Auth::logout();
        return view('admin.login');
    }

    public function settings()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.settingsEdit', [
            'user' => Auth::user(),
        ]);
    }

    public function categories()
    {
        $user = Auth::user();
        App::setLocale($user->language);

        return view('admin.categoriesEdit', [
            'user' => Auth::user(),
        ]);
    }
}
