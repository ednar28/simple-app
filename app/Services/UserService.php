<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Create a user.
     *
     * @param  array<string, string>   $data
     */
    public function create(array $data, string $role = null): User
    {
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->save();

        if ($role) {
            $user->assignRole($role);
        }

        return $user;
    }

    /**
     * Update a user.
     *
     * @param  array<string, string>  $data
     * @return array<string, string|null>
     */
    public function update(User $user, array $data, string $role = null): array
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        if ($role) {
            $user->syncRoles($role);
        }

        return $user->only(['id', 'name', 'email', 'role']);
    }

    /**
     * Disable a user.
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function disable(User $user): array
    {
        $user->delete();

        return $user->only(['id', 'deleted_at']);
    }
}
