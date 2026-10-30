<?php

use App\Support\Tenancy\BelongsToTenant;

/*
 * ADR-002 promoted to an executable check: every module model is tenant-owned.
 */

foreach (['Customers', 'Catalog', 'Orders', 'Billing'] as $module) {
    arch("{$module} models are tenant-scoped")
        ->expect("App\\Modules\\{$module}\\Models")
        ->toUseTrait(BelongsToTenant::class);
}
