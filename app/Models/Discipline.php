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


// database/migrations/2025_07_06_create_students_table_and_update_roster_assignments.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTableAndUpdateRosterAssignments extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name')->encrypted();
            $table->timestamps();
        });

        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->after('roster_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->dropColumn('student_name');
        });
    }

    public function down()
    {
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->string('student_name')->after('roster_id');
            $table->dropForeign(['student_id']);
            $table->dropColumn('student_id');
        });
        Schema::dropIfExists('students');
    }
}