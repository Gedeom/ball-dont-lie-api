<?php

namespace App\Parsers;

use App\Parsers\Base\BaseParser;

class GameParser extends BaseParser
{
    protected $parseFields = [
        'always' => [
            'externalId' => 'external_id',
            'date',
            'datetime',
            'season',
            'status',
            'period',
            'time',
            'postseason',
            'homeTeamScore' => 'home_team_score',
            'visitorTeamScore' => 'visitor_team_score',
            'homeTeamId' => 'home_team_id',
            'visitorTeamId' => 'visitor_team_id',
        ],
        'import' => [
            'id' => 'externalId',
            'date',
            'datetime',
            'season',
            'status',
            'period',
            'time',
            'postseason',
            'home_team_score' => 'homeTeamScore',
            'visitor_team_score' => 'visitorTeamScore',
            'home_team_id' => 'homeTeamId',
            'visitor_team_id' => 'visitorTeamId',
        ]
    ];
}
