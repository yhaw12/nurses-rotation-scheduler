<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EncryptStudentNames extends Migration
{
    public function up()
    {
        // Change column to text to accommodate encrypted data
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->text('student_name')->change();
        });

        // Encrypt existing student names
        DB::table('roster_assignments')->orderBy('id')->chunk(100, function ($assignments) {
            foreach ($assignments as $assignment) {
                DB::table('roster_assignments')
                    ->where('id', $assignment->id)
                    ->update(['student_name' => encrypt($assignment->student_name)]);
            }
        });
    }

    public function down()
    {
        // Decrypt student names before reverting column type
        DB::table('roster_assignments')->orderBy('id')->chunk(100, function ($assignments) {
            foreach ($assignments as $assignment) {
                try {
                    $decrypted = decrypt($assignment->student_name);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $decrypted = 'Decryption Error';
                }
                DB::table('roster_assignments')
                    ->where('id', $assignment->id)
                    ->update(['student_name' => $decrypted]);
            }
        });

        // Revert column back to string
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->string('student_name')->change();
        });
    }
}