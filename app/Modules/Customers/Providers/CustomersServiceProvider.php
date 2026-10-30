<?php

namespace App\Modules\Customers\Providers;

use App\Modules\Customers\Contracts\CustomerDirectory;
use App\Modules\Customers\Internal\EloquentCustomerDirectory;
use Illuminate\Support\ServiceProvider;

class CustomersServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CustomerDirectory::class => EloquentCustomerDirectory::class,
    ];
}
