<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Modules\Catalog\Http\Resources\ProductResource;
use App\Modules\Catalog\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->orderBy('sku')->get());
    }

    public function store(Request $request): ProductResource
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        return new ProductResource(Product::create($data));
    }
}
