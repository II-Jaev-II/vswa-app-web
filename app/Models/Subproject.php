<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subproject extends Model
{
    protected $fillable = 
    [
        "project_name",
        "project_location",
        "project_id",
        "contractor",
        "project_type"
    ];
}
