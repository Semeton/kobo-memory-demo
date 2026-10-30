<?php

namespace App\Modules\Customers\Contracts;

final readonly class CustomerSummary
{
    public function __construct(
        public int $id,
        public string $displayName,
        public string $email,
    ) {
    }
}
