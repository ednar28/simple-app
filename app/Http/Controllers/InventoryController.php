<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
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
     *
     * @return \App\Http\Resources\ApiCollection
     */
    public function index()
    {
        return $this->inventoryService->getList();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequest  $request
     * @return \App\Models\Inventory
     */
    public function store(InventoryRequest $request)
    {
        $validated = $request->validated();

        return $this->inventoryService->create($validated);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InventoryRequest  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \App\Models\Inventory
     */
    public function update(InventoryRequest $request, Inventory $inventory)
    {
        $validated = $request->validated();

        return $this->inventoryService->update($inventory, $validated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return array<string, \Carbon\Carbon>
     */
    public function destroy(Inventory $inventory)
    {
        return $this->inventoryService->delete($inventory);
    }
}
