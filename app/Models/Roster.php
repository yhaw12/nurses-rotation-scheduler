<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = ['discipline_id', 'start_date', 'end_date', 'created_by'];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function assignments()
    {
        return $this->hasMany(RosterAssignment::class);
    }
}