<?php

namespace Tests\Feature\Request;

use App\Models\Inventory;
use Tests\TestCaseController;

class InventoryRequestTest extends TestCaseController
{
    private Inventory $inventory;

    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->inventory = Inventory::factory()->create();
    }

    /**
     * Test error message when field is not provided or empty.
     *
     * @return void
     */
    public function testRequired()
    {
        $form = [];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'price' => 'The price field is required.',
            'amount' => 'The amount field is required.',
            'unit' => 'The unit field is required.',
        ]);

        $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'name' => 'The name field is required.',
            'price' => 'The price field is required.',
            'amount' => 'The amount field is required.',
            'unit' => 'The unit field is required.',
        ]);
    }

    /**
     * Test error message when field is not a string.
     *
     * @return void
     */
    public function testString()
    {
        $form = [
            'name' => ['key' => 'value'],
        ];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'name' => 'The name must be a string.',
        ]);

        $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'name' => 'The name must be a string.',
        ]);
    }

    /**
     * Test error message when field is too long.
     *
     * @return void
     */
    public function testMaxLength()
    {
        $randomString = \Illuminate\Support\Str::random(500);
        $form = [
            'name' => $randomString,
        ];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'name' => 'The name must not be greater than 255 characters.',
        ]);

        $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'name' => 'The name must not be greater than 255 characters.',
        ]);
    }

    /**
     * Test error message when field is not an integer.
     *
     * @return void
     */
    public function testInteger()
    {
        $form = [
            'price' => 'some string',
            'amount' => 'some string',
        ];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'price' => 'The price must be an integer.',
            'amount' => 'The amount must be an integer.',
        ]);

        $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'price' => 'The price must be an integer.',
            'amount' => 'The amount must be an integer.',
        ]);
    }

    /**
     * Test error message when field is below minimum value.
     *
     * @return void
     */
    public function testMin()
    {
        $form = [
            'price' => -1,
            'amount' => -1,
        ];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'price' => 'The price must be at least 0.',
            'amount' => 'The amount must be at least 0.',
        ]);

        $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'price' => 'The price must be at least 0.',
            'amount' => 'The amount must be at least 0.',
        ]);
    }

    /**
     * Test error message when field is above the maximum value.
     *
     * @return void
     */
    public function testMax()
    {
        $form = [
            'price' => 999_999_999,
            'amount' => 999_999_999,
        ];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'price' => 'The price must not be greater than 100000000.',
            'amount' => 'The amount must not be greater than 100000000.',
        ]);

        $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'price' => 'The price must not be greater than 100000000.',
            'amount' => 'The amount must not be greater than 100000000.',
        ]);
    }

    /**
     * Test error message when field value invalid selected on rule.
     *
     * @return void
     */
    public function testIn()
    {
        $form = [
            'unit' => 'some text',
        ];

        $url = route('inventory.store');
        $this->postJson($url, $form)->assertJsonValidationErrors([
            'unit' => 'The selected unit is invalid.',
        ]);

         $url = route('inventory.update', ['inventory' => $this->inventory]);
        $this->putJson($url, $form)->assertJsonValidationErrors([
            'unit' => 'The selected unit is invalid.',
        ]);
    }
}
