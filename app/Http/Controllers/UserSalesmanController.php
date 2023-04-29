<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSalesmanRequest;
use App\Http\Resources\ApiCollection;
use App\Models\User;
use App\Services\UserSalesmanService;

class UserSalesmanController extends Controller
{
    private UserSalesmanService $userSalesmanService;

    public function __construct()
    {
        $this->userSalesmanService = new UserSalesmanService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ApiCollection
    {
        return $this->userSalesmanService->getList();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserSalesmanRequest $request)
    {
        $validated = $request->validated();

        return $this->userSalesmanService->create(
            $validated['data'],
            $validated['salesman']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): User
    {
        return $this->userSalesmanService->getDetail($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return array<string, mixed>
     */
    public function update(UserSalesmanRequest $request, User $user): array
    {
        $validated = $request->validated();

        return $this->userSalesmanService->update(
            $user,
            $validated['data'],
            $validated['salesman']
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return array<string, \Carbon\Carbon|int>
     */
    public function destroy(User $user): array
    {
        return $this->userSalesmanService->disable($user);
    }
}
