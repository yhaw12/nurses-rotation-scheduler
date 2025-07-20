<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = ['name'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }
}


