<?php

namespace App\Modules\Billing\Models;

use App\Support\Money\MoneyCast;
use App\Support\Tenancy\BelongsToTenant;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use BelongsToTenant, HasFactory;

    protected $fillable = ['customer_id', 'number', 'amount', 'status', 'due_on'];

    protected function casts(): array
    {
        return [
            'amount' => MoneyCast::class,
            'due_on' => 'date',
        ];
    }

    protected static function newFactory(): InvoiceFactory
    {
        return InvoiceFactory::new();
    }
}
