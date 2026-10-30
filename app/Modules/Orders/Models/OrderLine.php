<?php

namespace App\Modules\Orders\Models;

use App\Support\Money\MoneyCast;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLine extends Model
{
    use BelongsToTenant;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'line_total'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => MoneyCast::class,
            'line_total' => MoneyCast::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
