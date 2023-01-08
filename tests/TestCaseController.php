<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserAdminSeeder;

class TestCaseController extends TestCase
{
    protected User $user;

    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(UserAdminSeeder::class);

        /** @var \App\Models\User */
        $this->user = User::latest()->first();

        $this->actingAs($this->user);
    }
}
