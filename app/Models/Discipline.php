<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function units()
    {
        return $this->hasMany(Unit::class)->orderBy('sort_order');
    }

    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }

    public function nurses()
    {
        return $this->hasMany(Nurse::class);
    }
}