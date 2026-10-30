<?php

namespace App\Support\Tenancy;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Demo-grade tenant resolution: the tenant id arrives in the X-Tenant header.
 * (A real deployment resolves it from the authenticated user or the domain.)
 */
final class ResolveTenantFromHeader
{
    public function __construct(private TenantContext $context)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->header('X-Tenant');

        if (! is_numeric($id) || ! Tenant::query()->whereKey((int) $id)->exists()) {
            abort(400, 'A valid X-Tenant header is required.');
        }

        $this->context->set((int) $id);

        return $next($request);
    }
}
