<?php

use App\Support\Tenancy\Tenant;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extend(TestCase::class)->in('Unit');

/**
 * Bind a tenant for code that runs outside an HTTP request (factories, direct queries).
 */
function actingAsTenant(Tenant $tenant): Tenant
{
    app(TenantContext::class)->set($tenant->id);

    return $tenant;
}

/**
 * @return array{X-Tenant: string}
 */
function tenantHeaders(Tenant $tenant): array
{
    return ['X-Tenant' => (string) $tenant->id];
}
