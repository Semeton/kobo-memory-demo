<?php

namespace App\Modules\Customers\Http\Controllers;

use App\Modules\Customers\Http\Resources\CustomerResource;
use App\Modules\Customers\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController
{
    public function index(): AnonymousResourceCollection
    {
        return CustomerResource::collection(Customer::query()->orderBy('id')->get());
    }

    public function store(Request $request): CustomerResource
    {
        $data = $request->validate([
            'display_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        return new CustomerResource(Customer::create($data));
    }
}
