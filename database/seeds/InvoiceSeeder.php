<?php

namespace database\seeds;

use App\Customer;
use App\Invoice;
use App\Order;
use App\Product;
use App\Returned;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

/**
 * Builds a month of trading history: each sale becomes an order, and most of
 * those orders are invoiced. The admin dashboard reads these tables directly,
 * so this is what makes its charts and totals show real numbers.
 */
class InvoiceSeeder extends Seeder
{
    /** How many days of history to generate. */
    private $days = 30;

    public function run()
    {
        $customers = Customer::all();
        $products = Product::all();
        $users = User::where('type', 'admin')->get();

        if ($customers->isEmpty() || $products->isEmpty() || $users->isEmpty()) {
            $this->command->getOutput()->writeln('<comment>Skipping invoices: seed customers, products and users first.</comment>');
            return;
        }

        for ($day = $this->days; $day >= 0; $day--) {
            // Busier towards the weekend, so the chart has a visible shape.
            $date = Carbon::now()->subDays($day);
            $salesToday = $date->dayOfWeek >= 5 ? rand(2, 5) : rand(0, 3);

            for ($i = 0; $i < $salesToday; $i++) {
                $at = $date->copy()->setTime(rand(8, 19), rand(0, 59));
                $this->createSale($customers->random(), $users->random(), $products, $at);
            }
        }
    }

    private function createSale(Customer $customer, User $user, $products, Carbon $at)
    {
        $lines = $this->pickLines($products);

        $order = Order::create($this->partyDetails($customer) + [
            'customer_id' => $customer->id,
            'user_id' => $user->id,
        ]);
        $this->attachLines('order_product', 'order_id', $order->id, $lines, $at);

        // Roughly one order in six is still open and never gets invoiced.
        $invoiced = rand(1, 6) > 1;
        $this->stampTimestamps('orders', $order->id, $at, ['completed' => $invoiced]);

        if (! $invoiced) {
            return;
        }

        $totals = $this->totals($lines);

        $invoice = new Invoice($this->partyDetails($customer) + [
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'taxed_total' => $totals['taxed'],
            'untaxed_total' => $totals['untaxed'],
            'taxes_total' => $totals['taxes'],
            'due_at' => $at->copy()->addDays(14),
            'paid' => rand(1, 10) > 3,
        ]);
        $invoice->order_id = $order->id;
        $invoice->costs_total = $totals['costs'];
        $invoice->save();

        $this->attachLines('invoice_product', 'invoice_id', $invoice->id, $lines, $at);
        $this->stampTimestamps('invoices', $invoice->id, $at);

        // A small share of sales comes back, so the returns widget has something to show.
        if (rand(1, 100) <= 8) {
            $this->createReturn($customer, $user, $order, $lines[0], $at);
        }
    }

    private function createReturn(Customer $customer, User $user, Order $order, array $line, Carbon $at)
    {
        $returnedAt = $at->copy()->addDays(rand(1, 5));

        if ($returnedAt->isFuture()) {
            $returnedAt = Carbon::now();
        }

        $return = new Returned($this->partyDetails($customer) + [
            'order_id' => $order->id,
            'user_id' => $user->id,
        ]);
        $return->customer_id = $customer->id;
        $return->rma = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $return->save();

        $this->attachReturnLine($return->id, $line['product'], $returnedAt);

        $this->stampTimestamps('returns', $return->id, $returnedAt);
    }

    /** Two to five distinct products with sensible quantities. */
    private function pickLines($products)
    {
        $lines = [];

        foreach ($products->random(min(rand(2, 5), $products->count())) as $product) {
            $lines[] = [
                'product' => $product,
                'quantity' => rand(1, 12),
            ];
        }

        return $lines;
    }

    private function totals(array $lines)
    {
        $untaxed = $taxed = $costs = 0;

        foreach ($lines as $line) {
            $product = $line['product'];
            $lineTotal = $product->priceEach * $line['quantity'];

            $untaxed += $lineTotal;
            $taxed += $lineTotal * (100 + $product->taxPercent) / 100;
            $costs += $product->basePrice * $line['quantity'];
        }

        return [
            'untaxed' => round($untaxed, 2),
            'taxed' => round($taxed, 2),
            'taxes' => round($taxed - $untaxed, 2),
            'costs' => round($costs, 2),
        ];
    }

    /**
     * Write pivot rows dated to the sale, not to now -- the best sellers widget
     * filters on the pivot's own created_at.
     */
    private function attachLines($table, $foreignKey, $id, array $lines, Carbon $at)
    {
        foreach ($lines as $line) {
            DB::table($table)->insert([
                $foreignKey => $id,
                'product_id' => $line['product']->id,
                'quantity' => $line['quantity'],
                'priceEach' => $line['product']->priceEach,
                'taxPercent' => $line['product']->taxPercent,
                'isPriceEdited' => false,
                'created_at' => $at,
                'updated_at' => $at,
            ]);
        }
    }

    /** return_product has no isPriceEdited column, so it needs its own insert. */
    private function attachReturnLine($returnId, $product, Carbon $at)
    {
        DB::table('return_product')->insert([
            'return_id' => $returnId,
            'product_id' => $product->id,
            'quantity' => 1,
            'priceEach' => $product->priceEach,
            'taxPercent' => $product->taxPercent,
            'created_at' => $at,
            'updated_at' => $at,
        ]);
    }

    /** Backdate a row; Eloquent would otherwise stamp it with the current time. */
    private function stampTimestamps($table, $id, Carbon $at, array $extra = [])
    {
        DB::table($table)->where('id', $id)->update($extra + [
            'created_at' => $at,
            'updated_at' => $at,
        ]);
    }

    /** Copy the billing details off the customer onto the document. */
    private function partyDetails(Customer $customer)
    {
        return [
            'entityType' => $customer->type,
            'company' => $customer->company,
            'name' => $customer->name,
            'surname' => $customer->surname,
            'email1' => $customer->email1,
            'phone' => $customer->phone,
            'mobile' => $customer->mobile,
            'vatid' => $customer->vatid ?: 'FI00000000',
            'taxid' => $customer->taxid ?: 'TAX000000',
            'street1' => $customer->street1,
            'street2' => $customer->street2,
            'city' => $customer->city,
            'state' => $customer->state,
            'zipcode' => $customer->zipcode,
            'country' => $customer->country,
        ];
    }
}
