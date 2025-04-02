<?php

namespace App\Repositories;

use App\Models\ExternalApiToken;
use App\Repositories\Base\BaseRepository;

class ExternalApiTokenRepository extends BaseRepository
{
    protected $entity = ExternalApiToken::class;

    public function getByToken(string $token)
    {
        return $this->entity::where("token", $token)->first();
    }
}
