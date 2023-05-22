<?php

namespace Tests\Feature\Controller;

use App\Enums\Units;
use App\Models\Product;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCaseController;

class ProductControllerTest extends TestCaseController
{
    /**
     * Setup environment testing.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->url = route('product.index');
    }

    /**
     * Test ProductController@index. Should has these attributes.
     */
    public function testIndexAttributes(): void
    {
        $product = Product::factory()->create();

        $this->getJsonSuccess()->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 1, fn (AssertableJson $json) => $json
                ->where('id', $product->id)
                ->where('name', $product->name)
                ->where('price', $product->price)
                ->where('amount', $product->amount)
                ->where('unit', $product->unit)
                ->where('created_at', $product->created_at->toJSON())
                ->where('updated_at', $product->updated_at->toJSON())
                ->where('deleted_at', null)
            )
            ->has('links')
            ->has('meta', fn (AssertableJson $json) => $json
                ->where('current_page', 1)
                ->where('to', 1)
                ->where('total', 1)
                ->where('per_page', 50)
                ->etc()
            )
        );
    }

    /**
     * Test ProductController@index. Should ordered by request.
     */
    public function testIndexOrder(): void
    {
        $products = Product::factory()->count(3)->sequence(
            ['name' => 'abc', 'price' => 30_000, 'amount' => 2],
            ['name' => 'cookie', 'price' => 20_000, 'amount' => 0],
            ['name' => 'milk', 'price' => 10_000, 'amount' => 10],
        )->create();

        // default should order by name ascending
        $this->getJsonSuccess()->assertJson(fn (AssertableJson $json) => $json
            ->has('data', 3)
            ->where('data.0.id', $products[0]->id)
            ->where('data.1.id', $products[1]->id)
            ->where('data.2.id', $products[2]->id)
            ->etc()
        );

        // order by name | descending
        $this->getJsonSuccess(data: ['order_column' => 'name', 'order_type' => 'desc'])
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $products[2]->id)
                ->where('data.1.id', $products[1]->id)
                ->where('data.2.id', $products[0]->id)
                ->etc()
            );

        // order by price | ascending
        $this->getJsonSuccess(data: ['order_column' => 'price'])
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $products[2]->id)
                ->where('data.1.id', $products[1]->id)
                ->where('data.2.id', $products[0]->id)
                ->etc()
            );

        // order by price | descending
        $this->getJsonSuccess(data: ['order_column' => 'price', 'order_type' => 'desc'])
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $products[0]->id)
                ->where('data.1.id', $products[1]->id)
                ->where('data.2.id', $products[2]->id)
                ->etc()
            );

        // order by amount | ascending
        $this->getJsonSuccess(data: ['order_column' => 'amount'])
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $products[1]->id)
                ->where('data.1.id', $products[0]->id)
                ->where('data.2.id', $products[2]->id)
                ->etc()
            );

        // order by amount | descending
        $this->getJsonSuccess(data: ['order_column' => 'amount', 'order_type' => 'desc'])
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->where('data.0.id', $products[2]->id)
                ->where('data.1.id', $products[0]->id)
                ->where('data.2.id', $products[1]->id)
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

        $response = $this->postJsonSuccess($form);
        $product = Product::orderByDesc('id')->first();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $product->id)
            ->where('name', $form['name'])
            ->where('price', $form['price'])
            ->where('amount', $form['amount'])
            ->where('unit', $form['unit'])
            ->where('created_at', now()->toJSON())
            ->where('updated_at', now()->toJSON())
        );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $form['name'],
            'price' => $form['price'],
            'amount' => $form['amount'],
            'unit' => $form['unit'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test ProductController@update.
     *
     * @return void
     */
    public function testUpdate()
    {
        $product = Product::factory()->create();

        $form = [
            'name' => 'edamame',
            'price' => 240_000,
            'amount' => 20,
            'unit' => Units::DOZEN->value,
        ];

        $this->travel(2)->days();
        $this->putJsonSuccess($form, $product->id)
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('id', $product->id)
                ->where('name', $form['name'])
                ->where('price', $form['price'])
                ->where('amount', $form['amount'])
                ->where('unit', $form['unit'])
                ->where('created_at', $product->created_at->toJSON())
                ->where('updated_at', now()->toJSON())
                ->where('deleted_at', null)
            );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $form['name'],
            'price' => $form['price'],
            'amount' => $form['amount'],
            'unit' => $form['unit'],
            'created_at' => $product->created_at,
            'updated_at' => now(),
        ]);
    }

    /**
     * Test ProductController@destroy.
     *
     * @return void
     */
    public function testDestroy()
    {
        $product = Product::factory()->create();

        $this->deleteJsonSuccess($product->id)->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $product->id)
            ->where('deleted_at', now()->toJSON())
        );
    }
}
