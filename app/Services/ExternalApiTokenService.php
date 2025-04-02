<?php

namespace App\Services;

use App\Contracts\ExternalApiTokenRepositoryInterface;
use App\Services\Base\BaseService;
use Illuminate\Support\Str;

class ExternalApiTokenService extends BaseService
{
    protected $repositoryClass = ExternalApiTokenRepositoryInterface::class;

    protected $parseFields = [
        'always' => [
            'name'
        ]
    ];


    public function create(array $entityData)
    {
        $token = Str::random(60);
        $entityData['token'] = hash('sha256', $token);

        return parent::create($entityData);
    }

    public function getByToken(string $token)
    {
        return $this->repositoryClass->getByToken($token);
    }
}
