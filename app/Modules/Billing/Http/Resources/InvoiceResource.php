<?php

namespace App\Modules\Billing\Http\Resources;

use App\Modules\Billing\Models\Invoice;
use App\Modules\Customers\Contracts\CustomerSummary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invoice
 */
class InvoiceResource extends JsonResource
{
    private ?CustomerSummary $customer = null;

    public function withCustomer(?CustomerSummary $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'amount' => $this->amount->toNaira(),
            'status' => $this->status,
            'due_on' => $this->due_on->toDateString(),
            'customer' => $this->customer ? [
                'id' => $this->customer->id,
                'display_name' => $this->customer->displayName,
            ] : null,
        ];
    }
}
