<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = ['discipline_id', 'name'];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function rosterUnits()
    {
        return $this->hasMany(RosterUnit::class);
    }

    public function rosters()
    {
        return $this->belongsToMany(Roster::class, 'roster_units')->withPivot(['start_date','end_date']);
    }
}