<?php

namespace App\Services;

use App\Http\Resources\ApiCollection;
use App\Models\Inventory;

class InventoryService
{
    /**
     * Get list inventories
     *
     * @return \App\Http\Resources\ApiCollection
     */
    public function getList()
    {
        $inventories = Inventory::query()
            ->requestSort(request()->get('order_column'), request()->get('order_type'))
            ->paginate();

        return new ApiCollection($inventories);
    }

    /**
     * Create a inventory.
     *
     * @param  array<string, int|string>  $data
     * @return \App\Models\Inventory
     */
    public function create(array $data)
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
     *
     * @param  \App\Models\Inventory  $inventory
     * @param  array<string, int|string>  $data
     * @return \App\Models\Inventory
     */
    public function update(Inventory $inventory, array $data)
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
     * @param  \App\Models\Inventory  $inventory
     * @return array<string, \Carbon\Carbon>
     */
    public function delete(Inventory $inventory)
    {
        $inventory->delete();

        return [
            'deleted_at' => $inventory->deleted_at,
        ];
    }
}
