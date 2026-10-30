<?php

namespace App\Modules\Orders\Http\Resources;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderLine;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'subtotal' => $this->subtotal->toNaira(),
            'total' => $this->total->toNaira(),
            'shipping_address' => $this->shipping_address,
            'lines' => $this->whenLoaded('lines', fn () => $this->lines->map(fn (OrderLine $line) => [
                'product_id' => $line->product_id,
                'quantity' => $line->quantity,
                'unit_price' => $line->unit_price->toNaira(),
                'line_total' => $line->line_total->toNaira(),
            ])),
        ];
    }
}
