<?php

namespace App\Modules\Orders\Models;

use App\Support\Money\MoneyCast;
use App\Support\Tenancy\BelongsToTenant;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use BelongsToTenant, HasFactory;

    protected $fillable = ['customer_id', 'number', 'subtotal', 'total', 'status', 'shipping_address'];

    protected function casts(): array
    {
        return [
            'subtotal' => MoneyCast::class,
            'total' => MoneyCast::class,
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
