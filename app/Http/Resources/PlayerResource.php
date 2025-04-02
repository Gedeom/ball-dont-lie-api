<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'externalId' => $this->external_id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'position' => $this->position,
            'height' => $this->height,
            'weight' => $this->weight,
            'jerseyNumber' => $this->jersey_number,
            'college' => $this->college,
            'country' => $this->country,
            'draftYear' => $this->draft_year,
            'draftRound' => $this->draft_round,
            'draftNumber' => $this->draft_number,
            'teamId' => $this->team_id,
        ];
    }
}
