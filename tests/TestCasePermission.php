<?php

namespace Tests;

use App\Enums\Roles;
use App\Models\User;
use App\Models\UserSalesman;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\DB;

class TestCasePermission extends TestCase
{
    protected User $admin;
    protected User $user;
    protected User $salesman;

    private int $totalUser = 3;

    /**
     * value only GET|POST|PUT|DELETE
     */
    private string $isMethod = 'GET';

    /** @var <int, mixed> */
    private array $form;

    /**
     * Setup environment testing.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $users = User::factory()->count($this->totalUser)->create();

        $this->admin = $users->shift();
        $this->admin->assignRole(Roles::SUPERADMIN->value);

        $this->salesman = $users->shift();
        UserSalesman::factory()->create(['user_id' => $this->salesman->id]);

        $this->user = $users->shift();
    }

    /**
     * Execute check permission.
     */
    protected function assertPermission(
        bool $admin,
        bool $salesman,
        bool $user,
    ): void {
        DB::beginTransaction();

        $this->actingAs($this->admin)->checkAccess($admin, $this->form);
        DB::rollBack();

        $this->actingAs($this->salesman)->checkAccess($salesman, $this->form);
        DB::rollBack();

        $this->actingAs($this->user)->checkAccess($user, $this->form);
        DB::rollBack();
    }

    /**
     * Get Test Response
     */
    private function checkAccess(bool $canAccess, array $form = []): self
    {
        switch($this->isMethod) {
            case 'POST':
                $testResponse = $this->postJson($this->url, $form);
                break;
            case 'PUT':
                $testResponse = $this->putJson($this->url, $form);
                break;
            case 'DELETE':
                $testResponse = $this->deleteJson($this->url, $form);
                break;
            default:
                $testResponse = $this->getJson($this->url, $form);
                break;
        }

        if ($canAccess) {
            $testResponse->assertSuccessful();
        } else {
            $testResponse->assertForbidden();
        }

        return $this;
    }

    /**
     * Set method GET
     */
    protected function getJsonPermission(): self
    {
        $this->isMethod = 'GET';
        return $this;
    }

    /**
     * Set method POST
     */
    protected function postJsonPermission(array $form = []): self
    {
        $this->isMethod = 'POST';
        $this->form = $form;
        return $this;
    }

    /**
     * Set method PUT
     */
    protected function putJsonPermission(array $form = []): self
    {
        $this->isMethod = 'PUT';
        $this->form = $form;
        return $this;
    }

    /**
     * Set method DELETE
     */
    protected function deleteJsonPermission(array $form = []): self
    {
        $this->isMethod = 'DELETE';
        $this->form = $form;
        return $this;
    }
}
