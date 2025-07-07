<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;

class RosterAssignment extends Model
{
    protected $fillable = ['roster_id', 'student_name', 'unit_id', 'subunit_id', 'start_date', 'end_date'];

    // Mutator to encrypt student_name when setting
    public function setStudentNameAttribute($value)
    {
        $this->attributes['student_name'] = encrypt($value);
    }

    // Accessor to decrypt student_name when getting
    public function getStudentNameAttribute($value)
    {
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            // Handle decryption failure gracefully
            return 'Decryption Error';
        }
    }

    // Define relationships if not already present
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