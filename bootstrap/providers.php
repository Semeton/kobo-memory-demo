<?php

use App\Modules\Catalog\Providers\CatalogServiceProvider;
use App\Modules\Customers\Providers\CustomersServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CustomersServiceProvider::class,
    CatalogServiceProvider::class,
];
