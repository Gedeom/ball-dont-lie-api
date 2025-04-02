<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'date' => $this->date,
            'datetime' => $this->datetime,
            'season' => $this->season,
            'status' => $this->status,
            'period' => $this->period,
            'time' => $this->time,
            'postseason' => $this->postseason,
            'homeTeamScore' => $this->home_team_score,
            'visitorTeamScore' => $this->visitor_team_score,
            'homeTeamId' => $this->home_team_id,
            'visitorTeamId' => $this->visitor_team_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
