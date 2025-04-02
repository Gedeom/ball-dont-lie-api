<?php

namespace App\Services;

use App\Contracts\GameRepositoryInterface;
use App\Services\Base\BaseService;
use App\Parsers\GameParser;

class GameService extends BaseService
{
    protected $repositoryClass = GameRepositoryInterface::class;
    protected $parserClass = GameParser::class;

    public function create(array $entityData)
    {
        if(isset($entityData['datetime'])) {
            $entityData['datetime'] = $this->parseValidDateTime($entityData['datetime']);
        }
        
        return parent::create($entityData);
    }

    public function update(int $id, array $entityData)
    {
        if(isset($entityData['datetime'])) {
            $entityData['datetime'] = $this->parseValidDateTime($entityData['datetime']);
        }

        return parent::update($id, $entityData);
    }

    private function parseValidDateTime(string $datetime)
    {
        $date = \DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $datetime);

        if(!$date) {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        }

        return $date->format('Y-m-d H:i:s');
    }

    public function getByExternalId($externalId)
    {
        return $this->repositoryClass->getByExternalId($externalId);
    }
}
