<?php

namespace App\Contracts;

use App\Contracts\Base\BaseRepositoryInterface;

interface PlayerRepositoryInterface extends BaseRepositoryInterface
{
    public function getByExternalId($externalId);
}