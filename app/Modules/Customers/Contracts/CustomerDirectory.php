<?php

namespace App\Modules\Customers\Contracts;

/**
 * The Customers module's public read API for other modules.
 */
interface CustomerDirectory
{
    public function find(int $customerId): ?CustomerSummary;

    /**
     * @param  list<int>  $customerIds
     * @return array<int, CustomerSummary> keyed by customer id
     */
    public function findMany(array $customerIds): array;
}
