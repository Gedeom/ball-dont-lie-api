<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\PlayerResource;
use App\Services\PlayerService;
use Illuminate\Http\Request;

class PlayerController extends BaseController
{
    protected $serviceClass = PlayerService::class;

    protected $resourceClass = PlayerResource::class;

    protected $rules = [
        'create' => [
            'externalId' => 'required|unique:players,external_id,deleted_at',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'position' => 'required|string',
            'height' => 'required|string',
            'weight' => 'required|numeric',
            'jerseyNumber' => 'required|numeric',
            'college' => 'required|string',
            'country' => 'required|string',
            'draftYear' => 'required|numeric',
            'draftRound' => 'required|numeric',
            'draftNumber' => 'required|numeric',
            'teamId' => 'required|numeric|exists:teams,id',
        ],
        'update'=> [
            'externalId' => 'sometimes|unique:players,external_id,deleted_at',
            'firstName' => 'sometimes|string',
            'lastName' => 'sometimes|string',
            'position' => 'sometimes|string',
            'height' => 'sometimes|string',
            'weight' => 'sometimes|numeric',
            'jerseyNumber' => 'sometimes|numeric',
            'college' => 'sometimes|string',
            'country' => 'sometimes|string',
            'draftYear' => 'sometimes|numeric',
            'draftRound' => 'sometimes|numeric',
            'draftNumber' => 'sometimes|numeric',
            'teamId' => 'sometimes|numeric|exists:teams,id',
        ]
    ];

    public function update(Request $request, $id)
    {
        $this->rules['update']['externalId'] = 'required|unique:players,external_id,' . $id . ',id,deleted_at,NULL'; 
        return parent::update($request, $id);
    }
}
