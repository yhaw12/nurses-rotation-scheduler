<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = ['discipline_id', 'start_date', 'end_date', 'reshuffle_index'];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function rosterUnits()
    {
        return $this->hasMany(RosterUnit::class);
    }

    public function nurses()
    {
        return $this->belongsToMany(Nurse::class, 'roster_units')->withPivot(['start_date','end_date']);
    }
}