<?php

use App\Modules\Billing\Models\Invoice;
use App\Modules\Customers\Models\Customer;
use App\Support\Tenancy\Tenant;
use Illuminate\Support\Facades\DB;

it('stores invoice amounts as integer kobo', function () {
    $tenant = Tenant::factory()->create();
    $customer = Customer::factory()->for($tenant)->create(['display_name' => 'Yaba Foods']);

    $this->postJson('/api/invoices', [
        'customer_id' => $customer->id,
        'amount' => '1500.50',
        'due_on' => '2026-11-30',
    ], tenantHeaders($tenant))
        ->assertCreated()
        ->assertJsonPath('data.amount', '1500.50')
        ->assertJsonPath('data.customer.display_name', 'Yaba Foods');

    expect(DB::table('invoices')->value('amount'))->toBe(150050);
});

it('shows an invoice with its customer', function () {
    $tenant = Tenant::factory()->create();
    $customer = Customer::factory()->for($tenant)->create(['display_name' => 'Surulere Hardware']);
    $invoice = Invoice::factory()->for($tenant)->create(['customer_id' => $customer->id]);

    $this->getJson("/api/invoices/{$invoice->id}", tenantHeaders($tenant))
        ->assertOk()
        ->assertJsonPath('data.customer.display_name', 'Surulere Hardware');
});
