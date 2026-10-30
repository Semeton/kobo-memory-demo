<?php

namespace App\Modules\Catalog\Providers;

use App\Modules\Catalog\Contracts\ProductCatalog;
use App\Modules\Catalog\Internal\EloquentProductCatalog;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public array $bindings = [
        ProductCatalog::class => EloquentProductCatalog::class,
    ];
}
