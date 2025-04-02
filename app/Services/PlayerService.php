<?php

namespace App\Services;

use App\Contracts\PlayerRepositoryInterface;
use App\Services\Base\BaseService;
use App\Parsers\PlayerParser;

class PlayerService extends BaseService
{
    protected $repositoryClass = PlayerRepositoryInterface::class;
    protected $parserClass = PlayerParser::class;

    public function getByExternalId($externalId)
    {
        return $this->repositoryClass->getByExternalId($externalId);
    }
}
