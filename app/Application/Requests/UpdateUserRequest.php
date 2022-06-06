<?php

namespace App\Application\Requests;

class UpdateUserRequest
{
    public int $userId;
    public ?string $name;
    public ?string $surname;
    public ?string $password;
    public ?string $repeatPassword;
    public ?string $dateOfBirth;
}
