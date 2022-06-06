<?php

namespace App\Application\Requests;

class CreateUserRequest
{
    public string $name;
    public string $surname;
    public string $email;
    public string $password;
    public string $repeatPassword;
    public string $dateOfBirth;
}
