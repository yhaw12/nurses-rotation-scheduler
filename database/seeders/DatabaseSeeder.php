<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
         $this->call(DisciplineSeeder::class);
        // $discipline = Discipline::create(['name' => 'Rgn']);
        // $unit = Unit::create(['name' => 'Unit opd', 'discipline_id' => $discipline->id]);
        // Subunit::create(['name' => 'Subunit 1', 'unit_id' => $unit->id, 'duration_weeks' => 4]);
        
    }
}




