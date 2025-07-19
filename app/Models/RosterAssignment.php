<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;

class RosterAssignment extends Model
{
    protected $fillable = ['roster_id', 'student_name', 'unit_id', 'subunit_id', 'start_date', 'end_date'];

    public function setStudentNameAttribute($value)
    {
        try {
            $this->attributes['student_name'] = encrypt($value);
        } catch (\Exception $e) {
            Log::error('Failed to encrypt student name: ' . $value, ['error' => $e->getMessage()]);
            $this->attributes['student_name'] = $value; // Fallback to plain text
        }
    }

    public function getStudentNameAttribute($value)
    {
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            Log::warning('Failed to decrypt student name: ' . ($value ?? 'null'), ['error' => $e->getMessage()]);
            return 'Decryption Error';
        }
    }

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