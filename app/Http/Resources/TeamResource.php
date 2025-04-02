<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'conference' => $this->conference,
            'division' => $this->division, 
            'city' => $this->city,
            'name' => $this->name,
            'fullName' => $this->full_name,
            'abbreviation' => $this->abbreviation,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
