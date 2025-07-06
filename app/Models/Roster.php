<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roster extends Model
{
    use SoftDeletes;

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