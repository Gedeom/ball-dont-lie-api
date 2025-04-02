<?php

namespace App\Services;

use App\Contracts\AuthRepositoryInterface;
use App\Services\Base\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService extends BaseService 
{
    protected $repositoryClass = AuthRepositoryInterface::class;

    public function createToken(string $email, string $password): string
    {
        $user = $this->repositoryClass->getByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        return $this->repositoryClass->createToken($user);
    }

    public function deleteTokens(int $userId): bool
    {
        return $this->repositoryClass->deleteTokens($userId);
    }
}
