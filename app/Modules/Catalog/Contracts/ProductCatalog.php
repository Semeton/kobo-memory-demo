<?php

namespace App\Modules\Catalog\Contracts;

use App\Support\Money\Money;

/**
 * The Catalog module's public read API for other modules.
 */
interface ProductCatalog
{
    public function exists(int $productId): bool;

    public function unitPrice(int $productId): Money;
}
