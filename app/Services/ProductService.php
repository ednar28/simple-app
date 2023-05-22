<?php

namespace App\Services;

use App\Http\Resources\ApiCollection;
use App\Models\Product;

class ProductService
{
    /**
     * Get list products
     */
    public function getList(): ApiCollection
    {
        $products = Product::query()
            ->requestSort(request()->get('order_column'), request()->get('order_type'))
            ->paginate();

        return new ApiCollection($products);
    }

    /**
     * Create a product.
     */
    public function create(array $data): Product
    {
        $product = new Product();
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->amount = $data['amount'];
        $product->unit = $data['unit'];
        $product->save();

        return $product;
    }

    /**
     * Update a product.
     */
    public function update(Product $product, array $data): Product
    {
        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->amount = $data['amount'];
        $product->unit = $data['unit'];
        $product->save();

        return $product;
    }

    /**
     * Soft delete a product
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function delete(Product $product): array
    {
        $product->delete();

        return $product->only(['id', 'deleted_at']);
    }
}
