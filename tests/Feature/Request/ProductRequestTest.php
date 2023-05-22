<?php

namespace Tests\Feature\Request;

use App\Models\Product;
use Tests\TestCaseController;

class ProductRequestTest extends TestCaseController
{
    private Product $product;

    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();

        $this->url = route('product.index');
    }

    /**
     * Test error message when field is not provided or empty.
     *
     * @return void
     */
    public function testRequired()
    {
        $form = [];

        $this->postJsonValidationErrors($form, [
            'name' => __('validation.required', ['attribute' => 'name']),
            'price' => __('validation.required', ['attribute' => 'price']),
            'amount' => __('validation.required', ['attribute' => 'amount']),
            'unit' => __('validation.required', ['attribute' => 'unit']),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'name' => __('validation.required', ['attribute' => 'name']),
            'price' => __('validation.required', ['attribute' => 'price']),
            'amount' => __('validation.required', ['attribute' => 'amount']),
            'unit' => __('validation.required', ['attribute' => 'unit']),
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

        $this->postJsonValidationErrors($form, [
            'name' => __('validation.string', ['attribute' => 'name']),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'name' => __('validation.string', ['attribute' => 'name']),
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

        $this->postJsonValidationErrors($form, [
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
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

        $this->postJsonValidationErrors($form, [
            'price' => __('validation.integer', ['attribute' => 'price']),
            'amount' => __('validation.integer', ['attribute' => 'amount']),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'price' => __('validation.integer', ['attribute' => 'price']),
            'amount' => __('validation.integer', ['attribute' => 'amount']),
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

        $this->postJsonValidationErrors($form, [
            'price' => __('validation.min.numeric', ['attribute' => 'price', 'min' => 0]),
            'amount' => __('validation.min.numeric', ['attribute' => 'amount', 'min' => 0]),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'price' => __('validation.min.numeric', ['attribute' => 'price', 'min' => 0]),
            'amount' => __('validation.min.numeric', ['attribute' => 'amount', 'min' => 0]),
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

        $this->postJsonValidationErrors($form, [
            'price' => __('validation.max.numeric', ['attribute' => 'price', 'max' => '100000000']),
            'amount' => __('validation.max.numeric', ['attribute' => 'amount', 'max' => '100000000']),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'price' => __('validation.max.numeric', ['attribute' => 'price', 'max' => '100000000']),
            'amount' => __('validation.max.numeric', ['attribute' => 'amount', 'max' => '100000000']),
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

        $this->postJsonValidationErrors($form, [
            'unit' => __('validation.in', ['attribute' => 'unit']),
        ]);

        $this->putJsonValidationErrors($form, $this->product->id, [
            'unit' => __('validation.in', ['attribute' => 'unit']),
        ]);
    }
}
