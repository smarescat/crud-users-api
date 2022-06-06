<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userId(): int
    {
        return $this->getAttributeValue('id');
    }

    public function name(): string
    {
        return $this->getAttributeValue('name');
    }

    public function setName(string $name): self
    {
        return $this->setAttribute('name', $name);
    }

    public function surname(): string
    {
        return $this->getAttribute('surname');
    }

    public function setSurname(string $surname): self
    {
        return $this->setAttribute('surname', $surname);
    }

    public function email(): string
    {
        return $this->getAttribute('email');
    }

    public function setEmail(string $email): self
    {
        return $this->setAttribute('email', $email);
    }

    public function password(): string
    {
        return $this->getAttribute('password');
    }

    public function setPassword(string $password): self
    {
        return $this->setAttribute('password', $password);
    }

    public function dateOfBirth(): Carbon
    {
        return Carbon::parse($this->getAttribute('date_of_birth'));
    }

    public function setDateOfBirth(string $dateOfBirth): self
    {
        return $this->setAttribute('date_of_birth', $dateOfBirth);
    }
}
