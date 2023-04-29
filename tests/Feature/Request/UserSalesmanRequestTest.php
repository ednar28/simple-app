<?php

namespace Tests\Feature\Request;

use App\Models\User;
use Tests\TestCaseController;

class UserSalesmanRequestTest extends TestCaseController
{
    private User $sales;

    /**
     * Setup environment testing.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sales = User::factory()->hasSalesman()->create();

        $this->url = route('user.salesman.index');
    }

    /**
     * Test error message when field is not provided or empty.
     */
    public function testRequired(): void
    {
        $form = [];

        $this->postJsonValidationErrors($form, [
            'data' => __('validation.required', ['attribute' => 'data']),
            'data.name' => __('validation.required', ['attribute' => 'name']),
            'data.email' => __('validation.required', ['attribute' => 'email']),
            'salesman' => __('validation.required', ['attribute' => 'salesman']),
            'salesman.code' => __('validation.required', ['attribute' => 'code']),
        ]);
        $this->putJsonValidationErrors($form, $this->sales->id, [
            'data' => __('validation.required', ['attribute' => 'data']),
            'data.name' => __('validation.required', ['attribute' => 'name']),
            'data.email' => __('validation.required', ['attribute' => 'email']),
            'salesman' => __('validation.required', ['attribute' => 'salesman']),
            'salesman.code' => __('validation.required', ['attribute' => 'code']),
        ]);
    }

    /**
     * Test error message when field is not an array.
     *
     * @return void
     */
    public function testArray()
    {
        $form = [
            'data' => 'some text',
            'salesman' => 'some text',
        ];

        $this->postJsonValidationErrors($form, [
            'data' => __('validation.array', ['attribute' => 'data']),
            'salesman' => __('validation.array', ['attribute' => 'salesman']),
        ]);
        $this->putJsonValidationErrors($form, $this->sales->id, [
            'data' => __('validation.array', ['attribute' => 'data']),
            'salesman' => __('validation.array', ['attribute' => 'salesman']),
        ]);
    }

    /**
     * Test error message when field is not a string.
     */
    public function testString(): void
    {
        $form = [
            'data' => [
                'name' => ['key' => 'value'],
                'password' => ['key' => 'value'],
            ],
            'salesman' => [
                'code' => ['key' => 'value'],
            ],
        ];

        $this->postJsonValidationErrors($form, [
            'data.name' => __('validation.string', ['attribute' => 'name']),
            'data.password' => __('validation.string', ['attribute' => 'password']),
            'salesman.code' => __('validation.string', ['attribute' => 'code']),
        ]);
        $this->putJsonValidationErrors($form, $this->sales->id, [
            'data.name' => __('validation.string', ['attribute' => 'name']),
            'salesman.code' => __('validation.string', ['attribute' => 'code']),
        ])->assertJsonMissingValidationErrors(['data.password']);
    }
}
