<?php

namespace App\Modules\Catalog\Internal;

use App\Modules\Catalog\Contracts\ProductCatalog;
use App\Modules\Catalog\Models\Product;
use App\Support\Money\Money;

final class EloquentProductCatalog implements ProductCatalog
{
    public function exists(int $productId): bool
    {
        return Product::query()->whereKey($productId)->exists();
    }

    public function unitPrice(int $productId): Money
    {
        return Money::fromNaira(Product::query()->findOrFail($productId)->price);
    }
}
