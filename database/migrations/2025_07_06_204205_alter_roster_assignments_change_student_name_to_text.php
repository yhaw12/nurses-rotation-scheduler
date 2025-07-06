<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->text('student_name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('roster_assignments', function (Blueprint $table) {
            $table->string('student_name')->change();
        });
    }
};