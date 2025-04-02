<?php

namespace App\Parsers;

use App\Parsers\Base\BaseParser;

class PlayerParser extends BaseParser
{
    protected $parseFields = [
        'always' => [
            'externalId' => 'external_id',
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'position',
            'height',
            'weight',
            'jerseyNumber' => 'jersey_number',
            'college',
            'country',
            'draftYear' => 'draft_year',
            'draftRound' => 'draft_round',
            'draftNumber' => 'draft_number',
            'teamId' => 'team_id',
        ],
        'import' => [
            'id' => 'externalId',
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'position',
            'height',
            'weight',
            'jersey_number' => 'jerseyNumber',
            'college',
            'country',
            'draft_year' => 'draftYear',
            'draft_round' => 'draftRound',
            'draft_number' => 'draftNumber',
            'team_id' => 'teamId',
        ]
    ];
}
