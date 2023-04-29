<?php

namespace Tests\Feature\Controller;

use App\Models\User;
use App\Models\UserSalesman;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCaseController;

class UserSalesmanControllerTest extends TestCaseController
{
    /**
     * Setup environment testing.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->url = route('user.salesman.index');
    }

    /**
     * Test UserSalesmanController@index. Should has these attributes.
     */
    public function testIndexAttributes(): void
    {
        $salesman = UserSalesman::factory()->create();

        $this->getJsonSuccess()->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 1, fn (AssertableJson $json) => $json
                    ->where('id', $salesman->user->id)
                    ->where('name', $salesman->user->name)
                    ->where('email', $salesman->user->email)
                    ->where('role', null)
                    ->where('email_verified_at', $salesman->user->email_verified_at->toJSON())
                    ->where('created_at', $salesman->user->created_at->toJSON())
                    ->where('updated_at', $salesman->user->updated_at->toJSON())
                    ->has('salesman', fn (AssertableJson $json) => $json
                        ->where('id', $salesman->id)
                        ->where('code', $salesman->code)
                        ->where('user_id', $salesman->user_id)
                    )
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
     * Test UserSalesmanController@index. Should ordered by name ascending.
     */
    public function testIndexOrder(): void
    {
        $users = User::factory()->count(3)
            ->sequence(
                ['name' => 'Sanji'],
                ['name' => 'Eren'],
                ['name' => 'Arlong'],
            )
            ->hasSalesman()
            ->create();

        $this->getJsonSuccess()->assertOk()->assertJson(fn (AssertableJson $json) => $json
            ->where('data.0.id', $users[2]->id)
            ->where('data.1.id', $users[1]->id)
            ->where('data.2.id', $users[0]->id)
            ->etc()
        );
    }

    /**
     * Test UserSalesmanController@store.
     */
    public function testStore(): void
    {
        $form = [
            'data' => [
                'name' => 'Roronoa Zoro',
                'email' => 'roronoa.z1@example.test',
                'password' => 'going_merry_06',
                'password_confirmation' => 'going_merry_06',
            ],
            'salesman' => [
                'code' => 'est_blue-12',
            ],
        ];

        $response = $this->postJsonSuccess($form);
        $user = User::latest('id')->first();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $user->id)
            ->where('name', $form['data']['name'])
            ->where('email', $form['data']['email'])
            ->where('role', null)
            ->has('salesman', fn (AssertableJson $json) => $json
                ->where('id', $user->salesman->id)
                ->where('user_id', $user->id)
                ->where('code', $form['salesman']['code'])
            )
            ->where('created_at', now()->toJSON())
            ->where('updated_at', now()->toJSON())
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $form['data']['name'],
            'email' => $form['data']['email'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('user_salesmen', [
            'id' => $user->salesman->id,
            'user_id' => $user->id,
            'code' => $form['salesman']['code'],
        ]);
    }

    /**
     * Test UserSalesmanController@update.
     */
    public function testUpdate(): void
    {
        $user = User::factory()->hasSalesman()->create();

        $form = [
            'data' => [
                'name' => 'Roronoa Zoro',
                'email' => 'roronoa.z1@example.test',
            ],
            'salesman' => [
                'code' => 'est_blue-12',
            ],
        ];

        $this->putJsonSuccess($form, $user->id)->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $user->id)
            ->where('name', $form['data']['name'])
            ->where('email', $form['data']['email'])
            ->where('role', null)
            ->has('salesman', fn (AssertableJson $json) => $json
                ->where('id', $user->salesman->id)
                ->where('code', $form['salesman']['code'])
            )
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $form['data']['name'],
            'email' => $form['data']['email'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('user_salesmen', [
            'id' => $user->salesman->id,
            'user_id' => $user->id,
            'code' => $form['salesman']['code'],
        ]);
    }

    /**
     * Test UserSalesmanController@destroy.
     */
    public function testDestroy(): void
    {
        $user = User::factory()->hasSalesman()->create();

        $this->deleteJsonSuccess($user->id)->assertJson(fn (AssertableJson $json) => $json
            ->where('id', $user->id)
            ->where('deleted_at', now()->toJSON())
        );

        $this->assertSoftDeleted($user);
    }
}
