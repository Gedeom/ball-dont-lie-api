<?php

namespace App\Parsers;

use App\Parsers\Base\BaseParser;

class TeamParser extends BaseParser
{
    protected $parseFields = [
        'always' => [
            'externalId' => 'external_id',
            'conference',
            'division', 
            'city',
            'name',
            'fullName' => 'full_name',
            'abbreviation',
        ],
        'import' => [
            'id' => 'externalId',
            'conference',
            'division', 
            'city',
            'name',
            'full_name' => 'fullName',
            'abbreviation',
        ]
    ];
}
