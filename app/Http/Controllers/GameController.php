<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\GameResource;
use App\Services\GameService;
use Illuminate\Http\Request;

class GameController extends BaseController
{
    protected $serviceClass = GameService::class;

    protected $resourceClass = GameResource::class;

    protected $rules = [
        'create' => [
            'externalId' => 'required|unique:games,external_id,deleted_at',
            'date' => 'required|date',
            'datetime' => 'required|date',
            'season' => 'required|numeric',
            'status' => 'required|string',
            'period' => 'required|numeric',
            'time' => 'sometimes',
            'postseason' => 'required|boolean',
            'homeTeamScore' => 'required|numeric',
            'visitorTeamScore' => 'required|numeric',
            'homeTeamId' => 'required|numeric|exists:teams,id',
            'visitorTeamId' => 'required|numeric|exists:teams,id|different:homeTeamId',
        ],
        'update'=> [
            'externalId' => 'sometimes|unique:games,external_id,deleted_at',
            'date' => 'sometimes|date',
            'datetime' => 'sometimes|date',
            'season' => 'sometimes|numeric',
            'status' => 'sometimes|string',
            'period' => 'sometimes|numeric',
            'time' => 'sometimes',
            'postseason' => 'sometimes|boolean',
            'homeTeamScore' => 'sometimes|numeric',
            'visitorTeamScore' => 'sometimes|numeric',
            'homeTeamId' => 'sometimes|numeric|exists:teams,id',
            'visitorTeamId' => 'sometimes|numeric|exists:teams,id|different:homeTeamId',
        ]
    ];

    public function update(Request $request, $id)
    {
        $this->rules['update']['externalId'] = 'required|unique:games,external_id,' . $id . ',id,deleted_at,NULL'; 
        return parent::update($request, $id);
    }
}
