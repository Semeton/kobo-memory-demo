<?php

namespace App\Modules\Customers\Internal;

use App\Modules\Customers\Contracts\CustomerDirectory;
use App\Modules\Customers\Contracts\CustomerSummary;
use App\Modules\Customers\Models\Customer;

final class EloquentCustomerDirectory implements CustomerDirectory
{
    public function find(int $customerId): ?CustomerSummary
    {
        $customer = Customer::query()->find($customerId);

        return $customer ? $this->toSummary($customer) : null;
    }

    public function findMany(array $customerIds): array
    {
        return Customer::query()
            ->whereKey($customerIds)
            ->get()
            ->mapWithKeys(fn (Customer $c) => [$c->id => $this->toSummary($c)])
            ->all();
    }

    private function toSummary(Customer $customer): CustomerSummary
    {
        return new CustomerSummary($customer->id, $customer->display_name ?? $customer->name, $customer->email);
    }
}
