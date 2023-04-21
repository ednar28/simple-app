<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Http\Resources\ApiCollection;
use App\Models\Inventory;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    private InventoryService $inventoryService;

    public function __construct()
    {
        $this->inventoryService = new InventoryService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ApiCollection
    {
        return $this->inventoryService->getList();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryRequest $request): Inventory
    {
        $validated = $request->validated();

        return $this->inventoryService->create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryRequest $request, Inventory $inventory): Inventory
    {
        $validated = $request->validated();

        return $this->inventoryService->update($inventory, $validated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function destroy(Inventory $inventory): array
    {
        return $this->inventoryService->delete($inventory);
    }
}
