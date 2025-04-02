<?php

namespace App\Contracts;

use App\Contracts\Base\BaseRepositoryInterface;

interface AuthRepositoryInterface extends BaseRepositoryInterface
{
    public function logout(array $data);
    public function getByEmail(string $email);
    public function createToken(object $user);
    public function deleteTokens(int $userId);
}