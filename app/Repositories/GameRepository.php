<?php

namespace App\Repositories;

use App\Models\Game;
use App\Repositories\Base\BaseRepository;

class GameRepository extends BaseRepository
{
    protected $entity = Game::class;

    public function getByExternalId($externalId)
    {
        return $this->entity::where('external_id', $externalId)->first();
    }
}
