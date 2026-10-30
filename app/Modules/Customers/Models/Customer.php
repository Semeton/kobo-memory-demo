<?php

namespace App\Modules\Customers\Models;

use App\Support\Tenancy\BelongsToTenant;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use BelongsToTenant, HasFactory;

    protected $fillable = ['display_name', 'email', 'phone'];

    protected static function booted(): void
    {
        // `name` is the pre-2026.04 column. Mobile v2 and self-hosted reporting
        // still read it, so keep writing it until the contract release drops it.
        static::saving(function (Customer $customer): void {
            if ($customer->display_name !== null) {
                $customer->name = $customer->display_name;
            }
        });
    }

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
