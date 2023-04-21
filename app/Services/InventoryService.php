<?php

namespace App\Services;

use App\Http\Resources\ApiCollection;
use App\Models\Inventory;

class InventoryService
{
    /**
     * Get list inventories
     */
    public function getList(): ApiCollection
    {
        $inventories = Inventory::query()
            ->requestSort(request()->get('order_column'), request()->get('order_type'))
            ->paginate();

        return new ApiCollection($inventories);
    }

    /**
     * Create a inventory.
     */
    public function create(array $data): Inventory
    {
        $inventory = new Inventory();
        $inventory->name = $data['name'];
        $inventory->price = $data['price'];
        $inventory->amount = $data['amount'];
        $inventory->unit = $data['unit'];
        $inventory->save();

        return $inventory;
    }

    /**
     * Update a inventory.
     */
    public function update(Inventory $inventory, array $data): Inventory
    {
        $inventory->name = $data['name'];
        $inventory->price = $data['price'];
        $inventory->amount = $data['amount'];
        $inventory->unit = $data['unit'];
        $inventory->save();

        return $inventory;
    }

    /**
     * Soft delete a inventory
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function delete(Inventory $inventory): array
    {
        $inventory->delete();

        return $inventory->only(['id', 'deleted_at']);
    }
}
