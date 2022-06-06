<?php

namespace App\Application;

use App\Application\Requests\CreateUserRequest;
use App\Application\Resources\UserResponseResource;
use App\Models\User;

class CreateUserUseCase
{
    public function execute(CreateUserRequest $request): UserResponseResource
    {
        $passwordHashed = hash('sha256', $request->password);

        $user = new User();

        $user->setName($request->name);
        $user->setSurname($request->surname);
        $user->setEmail($request->email);
        $user->setPassword($passwordHashed);
        $user->setDateOfBirth($request->dateOfBirth);

        $user->save();

        $resource = new UserResponseResource();

        $resource->userId = $user->userId();
        $resource->name = $user->name();
        $resource->surnames = $user->surname();
        $resource->email = $user->email();
        $resource->dateOfBirth = $user->dateOfBirth()->toIso8601ZuluString();

        return $resource;
    }
}
