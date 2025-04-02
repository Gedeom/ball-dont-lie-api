<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'external_id',
        'first_name',
        'last_name', 
        'position',
        'height',
        'weight',
        'jersey_number',
        'college',
        'country',
        'draft_year',
        'draft_round',
        'draft_number',
        'team_id',
    ];

    protected $casts = [
        'weight' => 'integer',
        'jersey_number' => 'integer',
    ];

    public function setWeightAttribute($value)
    {
        $this->attributes['weight'] = is_numeric($value) ? $value : null;
    }

    public function setJerseyNumberAttribute($value)
    {
        $this->attributes['jersey_number'] = is_numeric($value) ? $value : null;
    }
}
