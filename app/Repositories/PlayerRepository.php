<?php

namespace App\Repositories;

use App\Models\Player;
use App\Repositories\Base\BaseRepository;

class PlayerRepository extends BaseRepository
{
    protected $entity = Player::class;

    public function getByExternalId($externalId)
    {
        return $this->entity::where('external_id', $externalId)->first();
    }
}
