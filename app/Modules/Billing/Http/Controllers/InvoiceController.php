<?php

namespace App\Modules\Billing\Http\Controllers;

use App\Modules\Billing\Http\Resources\InvoiceResource;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Customers\Contracts\CustomerDirectory;
use App\Support\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class InvoiceController
{
    public function __construct(private CustomerDirectory $customers)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $invoices = Invoice::query()->orderByDesc('id')->get();
        $customers = $this->customers->findMany($invoices->pluck('customer_id')->unique()->values()->all());

        return InvoiceResource::collection($invoices)->additional(['customers' => $customers]);
    }

    public function show(Invoice $invoice): InvoiceResource
    {
        return (new InvoiceResource($invoice))->withCustomer($this->customers->find($invoice->customer_id));
    }

    public function store(Request $request): InvoiceResource
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer'],
            'amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'due_on' => ['required', 'date'],
        ]);

        $customer = $this->customers->find((int) $data['customer_id']);
        abort_if($customer === null, 422, 'Unknown customer.');

        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'number' => 'INV-'.Str::upper(Str::random(8)),
            'amount' => Money::fromNaira($data['amount']),
            'status' => 'open',
            'due_on' => $data['due_on'],
        ]);

        return (new InvoiceResource($invoice))->withCustomer($customer);
    }
}
