<?php

namespace App\Services;

use App\Http\Resources\ApiCollection;
use App\Models\User;
use App\Models\UserSalesman;
use Illuminate\Support\Facades\DB;

class UserSalesmanService
{
    /**
     * Get list salesmen.
     */
    public function getList(): ApiCollection
    {
        $salesmen = User::query()
            ->with(['roles', 'salesman'])
            ->join('user_salesmen', 'user_salesmen.user_id', '=', 'users.id')
            ->select('users.*')
            ->orderBy('users.name')
            ->paginate();

        return new ApiCollection($salesmen);
    }

    /**
     * Get detail salesman.
     */
    public function getDetail(User $user): User
    {
        return $user->load([
            'salesman',
        ]);
    }

    /**
     * Create a salesman.
     *
     * @param  array<string, string>  $dataUser
     * @param  array<string, string>  $dataSalesman
     * @return array<string, mixed>
     */
    public function create(array $dataUser, array $dataSalesman): array
    {
        return DB::transaction(function () use ($dataUser, $dataSalesman) {
            $userService = new UserService();
            $user = $userService->create($dataUser);

            $salesman = new UserSalesman();
            $salesman->code = $dataSalesman['code'];
            $salesman->user_id = $user->id;
            $salesman->save();

            return array_merge($user->toArray(), ['salesman' => $salesman]);
        });
    }

    /**
     * Update a salesman.
     *
     * @param  array<string, string>  $dataUser
     * @param  array<string, string>  $dataSalesman
     * @return array<string, mixed>
     */
    public function update(User $user, array $dataUser, array $dataSalesman): array
    {
        return DB::transaction(function () use ($user, $dataUser, $dataSalesman) {
            $userService = new UserService();
            $userUpdated = $userService->update($user, $dataUser);

            /** @var \App\Models\UserSalesman */
            $salesman = $user->salesman;
            $salesman->code = $dataSalesman['code'];
            $salesman->save();

            return array_merge($userUpdated, [
                'salesman' => $salesman->only(['id', 'code']),
            ]);
        });
    }

    /**
     * Disable a salesman.
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function disable(User $user): array
    {
        $userService = new UserService();

        return $userService->disable($user);
    }
}
