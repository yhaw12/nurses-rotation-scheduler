<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name', 'discipline_id', 'duration_weeks', 'sort_order'];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function subunits()
    {
        return $this->hasMany(Subunit::class);
    }
}