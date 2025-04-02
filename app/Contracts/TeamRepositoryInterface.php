<?php

namespace App\Contracts;

use App\Contracts\Base\BaseRepositoryInterface;

interface TeamRepositoryInterface extends BaseRepositoryInterface
{
    public function getByExternalId($externalId);
}