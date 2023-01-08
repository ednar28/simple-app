<?php

namespace Tests\Feature\Controller;

use App\Enums\Units;
use App\Models\Inventory;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCaseController;

class InventoryControllerTest extends TestCaseController
{
    /**
     * Test InventoryController@index. Should has these attributes.
     *
     * @return void
     */
    public function testIndexAttributes()
    {
        $inventory = Inventory::factory()->create();

        $this->getJson(route('inventory.index'))->assertOk()->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1, fn (AssertableJson $json) => $json
                ->where('id', $inventory->id)
                ->where('name', $inventory->name)
                ->where('price', $inventory->price)
                ->where('amount', $inventory->amount)
                ->where('unit', $inventory->unit)
                ->where('created_at', $inventory->created_at->toJSON())
                ->where('updated_at', $inventory->updated_at->toJSON())
                ->where('deleted_at', null)
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('current_page', 1)
                ->where('to', 1)
                ->where('total', 1)
                ->where('per_page', 15)
                ->etc()
            )
        );
    }

    /**
     * Test InventoryController@index. Should ordered by request.
     *
     * @return void
     */
    public function testIndexOrder()
    {
        $inventories = Inventory::factory()->count(3)->sequence(
            ['name' => 'abc', 'price' => 30_000, 'amount' => 2],
            ['name' => 'cookie', 'price' => 20_000, 'amount' => 0],
            ['name' => 'milk', 'price' => 10_000, 'amount' => 10],
        )->create();

        $url = route('inventory.index');

        // default should order by name ascending
        $this->getJson($url)->assertOk()->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 3)
            ->where('data.0.id', $inventories[0]->id)
            ->where('data.1.id', $inventories[1]->id)
            ->where('data.2.id', $inventories[2]->id)
            ->etc()
        );

        // order by name | descending
        $this->getJsonCustom($url, ['order_column' => 'name', 'order_type' => 'desc'])->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $inventories[2]->id)
                ->where('data.1.id', $inventories[1]->id)
                ->where('data.2.id', $inventories[0]->id)
                ->etc()
            );

        // order by price | ascending
        $this->getJsonCustom($url, ['order_column' => 'price'])->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $inventories[2]->id)
                ->where('data.1.id', $inventories[1]->id)
                ->where('data.2.id', $inventories[0]->id)
                ->etc()
            );

        // order by price | descending
        $this->getJsonCustom($url, ['order_column' => 'price', 'order_type' => 'desc'])->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $inventories[0]->id)
                ->where('data.1.id', $inventories[1]->id)
                ->where('data.2.id', $inventories[2]->id)
                ->etc()
            );

        // order by amount | ascending
        $this->getJsonCustom($url, ['order_column' => 'amount'])->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $inventories[1]->id)
                ->where('data.1.id', $inventories[0]->id)
                ->where('data.2.id', $inventories[2]->id)
                ->etc()
            );

        // order by amount | descending
        $this->getJsonCustom($url, ['order_column' => 'amount', 'order_type' => 'desc'])->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $inventories[2]->id)
                ->where('data.1.id', $inventories[0]->id)
                ->where('data.2.id', $inventories[1]->id)
                ->etc()
            );
    }

    /**
     * Test InvoiceController@store.
     *
     * @return void
     */
    public function testStore()
    {
        $form = [
            'name' => 'bread',
            'price' => 120_000,
            'amount' => 12,
            'unit' => Units::DOZEN->value,
        ];

        $response = $this->postJson(route('inventory.store'), $form)->assertCreated();
        $inventory = Inventory::orderByDesc('id')->first();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $inventory->id)
            ->where('name', $form['name'])
            ->where('price', $form['price'])
            ->where('amount', $form['amount'])
            ->where('unit', $form['unit'])
            ->where('created_at', now()->toJSON())
            ->where('updated_at', now()->toJSON())
        );

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'name' => $form['name'],
            'price' => $form['price'],
            'amount' => $form['amount'],
            'unit' => $form['unit'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test InventoryController@update.
     *
     * @return void
     */
    public function testUpdate()
    {
        $inventory = Inventory::factory()->create();

        $form = [
            'name' => 'edamame',
            'price' => 240_000,
            'amount' => 20,
            'unit' => Units::DOZEN->value,
        ];

        $url = route('inventory.update', ['inventory' => $inventory]);
        $this->travel(2)->days();
        $this->putJson($url, $form)->assertOk()->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $inventory->id)
            ->where('name', $form['name'])
            ->where('price', $form['price'])
            ->where('amount', $form['amount'])
            ->where('unit', $form['unit'])
            ->where('created_at', $inventory->created_at->toJSON())
            ->where('updated_at', now()->toJSON())
            ->where('deleted_at', null)
        );

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'name' => $form['name'],
            'price' => $form['price'],
            'amount' => $form['amount'],
            'unit' => $form['unit'],
            'created_at' => $inventory->created_at,
            'updated_at' => now(),
        ]);
    }

    /**
     * Test InventoryController@destroy.
     *
     * @return void
     */
    public function testDestroy()
    {
        $inventory = Inventory::factory()->create();

        $url = route('inventory.destroy', ['inventory' => $inventory]);
        $this->deleteJson($url)->assertOk()->assertJson(fn (AssertableJson $json) => $json
            ->where('deleted_at', now()->toJSON())
        );
    }
}
