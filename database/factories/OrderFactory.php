<?php

namespace Database\Factories;

use App\Modules\Orders\Models\Order;
use App\Support\Money\Money;
use App\Support\Tenancy\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'customer_id' => 1,
            'number' => 'ORD-'.strtoupper(fake()->unique()->bothify('????####')),
            'subtotal' => Money::ofKobo(500_000),
            'total' => Money::ofKobo(500_000),
            'status' => 'placed',
            'shipping_address' => '12 Admiralty Way, Lekki, Lagos',
        ];
    }
}
