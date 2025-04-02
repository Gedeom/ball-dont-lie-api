<?php

namespace App\Services;

use App\Contracts\TeamRepositoryInterface;
use App\Services\Base\BaseService;
use App\Parsers\TeamParser;

class TeamService extends BaseService
{
    protected $repositoryClass = TeamRepositoryInterface::class;
    protected $parserClass = TeamParser::class;

    public function getByExternalId($externalId)
    {
        return $this->repositoryClass->getByExternalId($externalId);
    }
}
