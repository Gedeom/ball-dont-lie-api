<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Services\ExternalApiTokenService;
use App\Http\Resources\ExternalApiTokenResource;


class ExternalApiTokenController extends BaseController
{
    protected $serviceClass = ExternalApiTokenService::class;

    protected $resourceClass = ExternalApiTokenResource::class;

    protected $rules = [
        'always' => [
            'name' => 'required',
        ],
    ];
}
