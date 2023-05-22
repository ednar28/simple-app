<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ApiCollection;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ApiCollection
    {
        return $this->productService->getList();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): Product
    {
        $validated = $request->validated();

        return $this->productService->create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        $validated = $request->validated();

        return $this->productService->update($product, $validated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function destroy(Product $product): array
    {
        return $this->productService->delete($product);
    }
}
