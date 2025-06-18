<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RosterUnit extends Model
{
    use HasFactory;

    protected $table = 'roster_units';
    protected $fillable = ['roster_id','nurse_id','unit_id','start_date','end_date'];

    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}