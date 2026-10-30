<?php

namespace Database\Factories;

use App\Modules\Billing\Models\Invoice;
use App\Support\Money\Money;
use App\Support\Tenancy\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'customer_id' => 1,
            'number' => 'INV-'.strtoupper(fake()->unique()->bothify('????####')),
            'amount' => Money::ofKobo(1_000_000),
            'status' => 'open',
            'due_on' => now()->addDays(14)->toDateString(),
        ];
    }
}
