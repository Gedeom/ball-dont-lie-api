<?php

namespace App\Contracts;

use App\Contracts\Base\BaseRepositoryInterface;

interface ExternalApiTokenRepositoryInterface extends BaseRepositoryInterface
{
    public function getByToken(string $token);
}