<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramOfWork extends Model
{
    protected $fillable = [
        'item_no',
        'scope_of_work',
        'quantity',
        'unit',
        'subproject_id',
    ];

    public function subproject()
    {
        return $this->belongsTo(Subproject::class);
    }
}
