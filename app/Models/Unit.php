<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['discipline_id', 'name', 'duration_weeks', 'sort_order'];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function rosterUnits()
    {
        return $this->hasMany(RosterUnit::class);
    }
}