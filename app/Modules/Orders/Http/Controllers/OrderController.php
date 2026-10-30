<?php

namespace App\Modules\Orders\Http\Controllers;

use App\Modules\Orders\Actions\PlaceOrder;
use App\Modules\Orders\Http\Requests\PlaceOrderRequest;
use App\Modules\Orders\Http\Resources\OrderResource;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController
{
    public function store(PlaceOrderRequest $request, PlaceOrder $placeOrder): JsonResponse
    {
        $order = $placeOrder->handle(
            (int) $request->validated('customer_id'),
            $request->validated('items'),
            $request->validated('shipping_address'),
        );

        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load('lines'));
    }
}
