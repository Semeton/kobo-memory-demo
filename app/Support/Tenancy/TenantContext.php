<?php

namespace App\Support\Tenancy;

/**
 * Holds the tenant for the current request or job.
 *
 * Bound as a singleton. Set by ResolveTenantFromHeader for HTTP requests;
 * console commands and jobs must call set() explicitly.
 */
final class TenantContext
{
    private ?int $tenantId = null;

    public function set(int $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    public function clear(): void
    {
        $this->tenantId = null;
    }

    public function has(): bool
    {
        return $this->tenantId !== null;
    }

    public function id(): int
    {
        if ($this->tenantId === null) {
            throw new MissingTenantContext('No tenant is bound to the current context.');
        }

        return $this->tenantId;
    }
}
