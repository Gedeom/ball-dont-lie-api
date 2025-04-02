<?php

namespace App\Repositories;

use App\Models\Team;
use App\Repositories\Base\BaseRepository;

class TeamRepository extends BaseRepository
{
    protected $entity = Team::class;

    public function getByExternalId($externalId)
    {
        return $this->entity::where('external_id', $externalId)->first();
    }
}
