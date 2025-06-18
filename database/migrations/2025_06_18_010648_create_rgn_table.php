<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. disciplines table
        Schema::create('disciplines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 2. units table
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('duration_weeks');
            $table->unsignedInteger('sort_order');
            $table->timestamps();
        });

        // 3. schedules table
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedInteger('reshuffle_index')->default(0);
            $table->timestamps();
        });

        // 4. nurses table
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // 5. nurse_unit_rotations table
        Schema::create('nurse_unit_rotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nurse_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurse_unit_rotations');
        Schema::dropIfExists('nurses');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('units');
        Schema::dropIfExists('disciplines');
    }
};
