<?php

namespace App\Application;

use App\Application\Resources\UserResponseResource;
use App\Models\User;

class GetUserUseCase
{
    public function execute(int $id): UserResponseResource
    {
        $user = User::find($id);

        $resource = new UserResponseResource();

        $resource->userId = $user->userId();
        $resource->name = $user->name();
        $resource->surnames = $user->surname();
        $resource->email = $user->email();
        $resource->dateOfBirth = $user->dateOfBirth()->toIso8601ZuluString();

        return $resource;
    }
}
