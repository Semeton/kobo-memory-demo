<?php

namespace App\Modules\Orders\Actions;

use App\Modules\Catalog\Contracts\ProductCatalog;
use App\Modules\Customers\Contracts\CustomerDirectory;
use App\Modules\Orders\Models\Order;
use App\Support\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class PlaceOrder
{
    public function __construct(
        private CustomerDirectory $customers,
        private ProductCatalog $catalog,
    ) {
    }

    /**
     * @param  list<array{product_id:int, quantity:int}>  $items
     */
    public function handle(int $customerId, array $items, string $shippingAddress): Order
    {
        if ($this->customers->find($customerId) === null) {
            throw ValidationException::withMessages(['customer_id' => 'Unknown customer.']);
        }

        return DB::transaction(function () use ($customerId, $items, $shippingAddress) {
            $subtotal = Money::zero();
            $lines = [];

            foreach ($items as $item) {
                if (! $this->catalog->exists($item['product_id'])) {
                    throw ValidationException::withMessages(['items' => "Unknown product {$item['product_id']}."]);
                }

                $unit = $this->catalog->unitPrice($item['product_id']);
                $lineTotal = $unit->times($item['quantity']);
                $subtotal = $subtotal->plus($lineTotal);

                $lines[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unit,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'customer_id' => $customerId,
                'number' => 'ORD-'.Str::upper(Str::random(8)),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'placed',
                'shipping_address' => $shippingAddress,
            ]);

            $order->lines()->createMany($lines);

            return $order->load('lines');
        });
    }
}
