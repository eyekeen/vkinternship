<?php

namespace App\Models;

use App\Application\Database\Model;

class User extends Model {

    protected string $table = 'users';
    protected array $fields = ['email', 'token', 'expired_at', 'password'];
    protected string $email;
    protected string $password;
    protected ?string $token = null;
    protected ?string $expired_at = null;

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getToken(): ?string {
        return $this->token;
    }
    public function getExpiredAt(): ?string {
        return $this->expired_at;
    }
}
