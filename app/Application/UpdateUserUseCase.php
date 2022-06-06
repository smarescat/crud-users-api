<?php

namespace App\Application;

use App\Application\Requests\UpdateUserRequest;
use App\Application\Resources\UserResponseResource;
use App\Models\User;
use Carbon\Carbon;

class UpdateUserUseCase
{
    /**
     * @param UpdateUserRequest $request
     * @return UserResponseResource
     */
    public function execute(UpdateUserRequest $request): UserResponseResource
    {
        /** @var User $user */
        $user = User::query()->where('id', '=', $request->userId)->first();

        $this->updateName($user, $request->name);
        $this->updateSurname($user, $request->surname);
        //$this->updatePassword($user, $request->password, $request->repeatPassword);
        $this->updateDateOfBirth($user, $request->dateOfBirth);

        $user->save();

        $resource = new UserResponseResource();
        $resource->userId = $user->userId();
        $resource->name = $user->name();
        $resource->surnames = $user->surname();
        $resource->dateOfBirth = $user->dateOfBirth()->toIso8601ZuluString();

        return $resource;
    }

    private function updateName(User $user, ?string $name): void
    {
        if ($name !== null) {
            $user->setName($name);
        }
    }

    private function updateSurname(User $user, ?string $surname): void
    {
        if ($surname !== null) {
            $user->setSurname($surname);
        }
    }

    /*private function updatePassword(User $user, ?string $password, ?string $repeatPassword): void
    {
        if ($password !== null && $repeatPassword != null && $password == $repeatPassword) {
            $user->setPassword(hash('sha256', $password));
        }
    }*/

    private function updateDateOfBirth(User $user, ?string $dateOfBirth): void
    {
        if ($dateOfBirth !== null) {
            $user->setDateOfBirth(Carbon::parse($dateOfBirth));
        }
    }
}
