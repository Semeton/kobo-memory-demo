<?php

use App\Modules\Customers\Models\Customer;
use App\Support\Tenancy\Tenant;
use Illuminate\Support\Facades\DB;

it('requires a tenant header', function () {
    $this->getJson('/api/customers')->assertStatus(400);
});

it('creates a customer and keeps writing the legacy name column', function () {
    $tenant = Tenant::factory()->create();

    $this->postJson('/api/customers', [
        'display_name' => 'Ikeja Pharmacy',
        'email' => 'ops@ikejapharmacy.ng',
    ], tenantHeaders($tenant))->assertCreated()->assertJsonPath('data.display_name', 'Ikeja Pharmacy');

    $row = DB::table('customers')->first();
    expect($row->display_name)->toBe('Ikeja Pharmacy')
        ->and($row->name)->toBe('Ikeja Pharmacy')
        ->and($row->tenant_id)->toBe($tenant->id);
});

it('only lists the current tenant\'s customers', function () {
    [$a, $b] = Tenant::factory()->count(2)->create();
    Customer::factory()->for($a)->create(['display_name' => 'Mine']);
    Customer::factory()->for($b)->create(['display_name' => 'Theirs']);

    $this->getJson('/api/customers', tenantHeaders($a))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.display_name', 'Mine');
});
