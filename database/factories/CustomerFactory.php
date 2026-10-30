<?php

namespace Database\Factories;

use App\Modules\Customers\Models\Customer;
use App\Support\Tenancy\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'display_name' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '0803'.fake()->numerify('#######'),
        ];
    }
}
