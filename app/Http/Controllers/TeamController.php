<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\TeamResource;
use App\Services\TeamService;
use Illuminate\Http\Request;

class TeamController extends BaseController
{
    protected $serviceClass = TeamService::class;

    protected $resourceClass = TeamResource::class;

    protected $rules = [
        'create' => [
            'externalId' => 'required|unique:teams,external_id,deleted_at',
            'conference' => 'required|string',
            'division' => 'required|string',
            'city' => 'required|string',
            'name' => 'required|string',
            'fullName' => 'required|string',
            'abbreviation' => 'required|string',
        ],
        'update'=> [
            'externalId' => 'sometimes|unique:teams,external_id,deleted_at',
            'conference' => 'sometimes|string',
            'division' => 'sometimes|string',
            'city' => 'sometimes|string',
            'name' => 'sometimes|string',
            'fullName' => 'sometimes|string',
            'abbreviation' => 'sometimes|string',
        ]
    ];

    public function update(Request $request, $id)
    {
        $this->rules['update']['externalId'] = 'required|unique:teams,external_id,' . $id . ',id,deleted_at,NULL'; 
        return parent::update($request, $id);
    }
}
