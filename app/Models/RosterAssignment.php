<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RosterAssignment extends Model
{
    protected $fillable = ['roster_id', 'student_name', 'unit_id', 'subunit_id', 'start_date', 'end_date'];

    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function subunit()
    {
        return $this->belongsTo(Subunit::class);
    }
}