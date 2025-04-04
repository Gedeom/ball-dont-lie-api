<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "external_id",
        "date",
        "datetime",
        "season",
        "status", 
        "period",
        "time",
        "postseason",
        "home_team_score",
        "visitor_team_score",
        "home_team_id",
        "visitor_team_id"
    ];
}
