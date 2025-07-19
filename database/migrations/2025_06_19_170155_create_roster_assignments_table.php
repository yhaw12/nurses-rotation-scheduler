<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roster_assignments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('roster_id')->constrained()->onDelete('cascade');
    $table->string('student_name');
    $table->foreignId('unit_id')->constrained()->onDelete('cascade');
    $table->foreignId('subunit_id')->constrained()->onDelete('cascade');
    $table->date('start_date');
    $table->date('end_date');
    $table->timestamps();


    // Add indexes for performance
            $table->index('roster_id');
            $table->index(['unit_id', 'subunit_id']);
            $table->index('start_date');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roster_assignments');
    }
};
