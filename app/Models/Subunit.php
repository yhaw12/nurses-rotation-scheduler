<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subunit extends Model
{
    protected $fillable = ['name', 'duration_weeks', 'unit_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}