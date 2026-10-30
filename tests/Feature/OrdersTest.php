<?php

use App\Modules\Catalog\Models\Product;
use App\Modules\Customers\Models\Customer;
use App\Modules\Orders\Models\Order;
use App\Support\Tenancy\Tenant;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
    $this->customer = Customer::factory()->for($this->tenant)->create();
    $this->product = Product::factory()->for($this->tenant)->create(['price' => '2500.00']);
});

it('places an order and prices it from the catalog', function () {
    $this->postJson('/api/orders', [
        'customer_id' => $this->customer->id,
        'items' => [['product_id' => $this->product->id, 'quantity' => 3]],
        'shipping_address' => '12 Admiralty Way, Lekki, Lagos',
    ], tenantHeaders($this->tenant))
        ->assertCreated()
        ->assertJsonPath('data.subtotal', '7500.00')
        ->assertJsonPath('data.total', '7500.00')
        ->assertJsonPath('data.lines.0.unit_price', '2500.00');

    actingAsTenant($this->tenant);
    expect(Order::query()->sole()->total->kobo)->toBe(750_000);
});

it('rejects unknown products', function () {
    $this->postJson('/api/orders', [
        'customer_id' => $this->customer->id,
        'items' => [['product_id' => 999, 'quantity' => 1]],
        'shipping_address' => 'Somewhere',
    ], tenantHeaders($this->tenant))->assertStatus(422);
});

it('does not show another tenant\'s order', function () {
    $other = Tenant::factory()->create();
    $order = Order::factory()->for($other)->create();

    $this->getJson("/api/orders/{$order->id}", tenantHeaders($this->tenant))->assertNotFound();
});
