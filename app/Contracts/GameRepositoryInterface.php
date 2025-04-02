<?php

namespace App\Contracts;

use App\Contracts\Base\BaseRepositoryInterface;

interface GameRepositoryInterface extends BaseRepositoryInterface
{
    public function getByExternalId($externalId);
}