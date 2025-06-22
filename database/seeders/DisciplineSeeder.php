<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\Unit;
use App\Models\Subunit;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    public function run()
    {
        // Seed Midwives Discipline
        $midwives = Discipline::firstOrCreate(['name' => 'Midwives']);

        $midwivesPublicHealth = Unit::firstOrCreate([
            'name' => 'Public Health',
            'discipline_id' => $midwives->id,
            'sort_order' => 1,
        ]);
        Subunit::firstOrCreate(['name' => 'CWC', 'unit_id' => $midwivesPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Nutrition', 'unit_id' => $midwivesPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'CHPS', 'unit_id' => $midwivesPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Home Visit', 'unit_id' => $midwivesPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Family Planning', 'unit_id' => $midwivesPublicHealth->id, 'duration_weeks' => 3]);

        $midwivesSurgicalNursing = Unit::firstOrCreate([
            'name' => 'Surgical Nursing',
            'discipline_id' => $midwives->id,
            'sort_order' => 2,
        ]);
        Subunit::firstOrCreate(['name' => 'Eye', 'unit_id' => $midwivesSurgicalNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'ENT', 'unit_id' => $midwivesSurgicalNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'MCH', 'unit_id' => $midwivesSurgicalNursing->id, 'duration_weeks' => 2]);

        $midwivesMaternity = Unit::firstOrCreate([
            'name' => 'Maternity',
            'discipline_id' => $midwives->id,
            'sort_order' => 3,
        ]);
        Subunit::firstOrCreate(['name' => 'ANC', 'unit_id' => $midwivesMaternity->id, 'duration_weeks' => 5]);
        Subunit::firstOrCreate(['name' => 'Labour/Delivery & Postnatal Care', 'unit_id' => $midwivesMaternity->id, 'duration_weeks' => 17]);
        Subunit::firstOrCreate(['name' => 'Neonatal Care (KPC)', 'unit_id' => $midwivesMaternity->id, 'duration_weeks' => 5]);
        Subunit::firstOrCreate(['name' => 'Gynae (MCH)', 'unit_id' => $midwivesMaternity->id, 'duration_weeks' => 1]);

        $midwivesMedicalNursing = Unit::firstOrCreate([
            'name' => 'Medical Nursing',
            'discipline_id' => $midwives->id,
            'sort_order' => 4,
        ]);
        Subunit::firstOrCreate(['name' => 'Male Ward', 'unit_id' => $midwivesMedicalNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Female/Kids', 'unit_id' => $midwivesMedicalNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Emergency', 'unit_id' => $midwivesMedicalNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'OPD', 'unit_id' => $midwivesMedicalNursing->id, 'duration_weeks' => 1]);

        $midwivesPsychiatry = Unit::firstOrCreate([
            'name' => 'Psychiatry (KPC)',
            'discipline_id' => $midwives->id,
            'sort_order' => 5,
        ]);
        Subunit::firstOrCreate(['name' => 'Comm. Psychiatry', 'unit_id' => $midwivesPsychiatry->id, 'duration_weeks' => 4]);

        // Seed RGN Discipline
        $rgn = Discipline::firstOrCreate(['name' => 'Registered General Nurses (RGN)']);

        $rgnMedicalNursing = Unit::firstOrCreate([
            'name' => 'Medical Nursing',
            'discipline_id' => $rgn->id,
            'sort_order' => 1,
        ]);
        Subunit::firstOrCreate(['name' => 'OPD', 'unit_id' => $rgnMedicalNursing->id, 'duration_weeks' => 12]);
        Subunit::firstOrCreate(['name' => 'Female Ward / Paediatric Ward', 'unit_id' => $rgnMedicalNursing->id, 'duration_weeks' => 12]);
        Subunit::firstOrCreate(['name' => 'Male Ward', 'unit_id' => $rgnMedicalNursing->id, 'duration_weeks' => 12]);
        Subunit::firstOrCreate(['name' => 'Emergency', 'unit_id' => $rgnMedicalNursing->id, 'duration_weeks' => 12]);

        $rgnSurgicalNursing = Unit::firstOrCreate([
            'name' => 'Surgical Nursing',
            'discipline_id' => $rgn->id,
            'sort_order' => 2,
        ]);
        Subunit::firstOrCreate(['name' => 'MCH', 'unit_id' => $rgnSurgicalNursing->id, 'duration_weeks' => 5]);
        Subunit::firstOrCreate(['name' => 'MCH (continued)', 'unit_id' => $rgnSurgicalNursing->id, 'duration_weeks' => 5]);

        $rgnObstetricsNursing = Unit::firstOrCreate([
            'name' => 'Obstetrics Nursing',
            'discipline_id' => $rgn->id,
            'sort_order' => 3,
        ]);
        Subunit::firstOrCreate(['name' => 'ANC (Antenatal Care)', 'unit_id' => $rgnObstetricsNursing->id, 'duration_weeks' => 4]);

        $rgnPublicHealth = Unit::firstOrCreate([
            'name' => 'Public Health',
            'discipline_id' => $rgn->id,
            'sort_order' => 4,
        ]);
        Subunit::firstOrCreate(['name' => 'Health Promotion/Education', 'unit_id' => $rgnPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Home Visit', 'unit_id' => $rgnPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Family Planning', 'unit_id' => $rgnPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'School Health', 'unit_id' => $rgnPublicHealth->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'CWC (Child Welfare Clinic)', 'unit_id' => $rgnPublicHealth->id, 'duration_weeks' => 2]);

        $rgnPsychiatry = Unit::firstOrCreate([
            'name' => 'Psychiatry',
            'discipline_id' => $rgn->id,
            'sort_order' => 5,
        ]);
        Subunit::firstOrCreate(['name' => 'Community Psychiatry (KPC)', 'unit_id' => $rgnPsychiatry->id, 'duration_weeks' => 3]);
        Subunit::firstOrCreate(['name' => 'Acute Ward / Chronic Ward (Outside)', 'unit_id' => $rgnPsychiatry->id, 'duration_weeks' => 3]);
        Subunit::firstOrCreate(['name' => 'Same or related rotations', 'unit_id' => $rgnPsychiatry->id, 'duration_weeks' => 3]);
        Subunit::firstOrCreate(['name' => 'Same or related rotations', 'unit_id' => $rgnPsychiatry->id, 'duration_weeks' => 3]);

        $rgnSpecialClinic = Unit::firstOrCreate([
            'name' => 'Special Clinic',
            'discipline_id' => $rgn->id,
            'sort_order' => 6,
        ]);
        Subunit::firstOrCreate(['name' => 'ENT (Ear, Nose, Throat)', 'unit_id' => $rgnSpecialClinic->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Eye', 'unit_id' => $rgnSpecialClinic->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'HIV/ART/CT', 'unit_id' => $rgnSpecialClinic->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Diabetic / Hypertensive', 'unit_id' => $rgnSpecialClinic->id, 'duration_weeks' => 2]);

        // Seed Public Health Nurses Discipline
        $publicHealthNurses = Discipline::firstOrCreate(['name' => 'Public Health Nurses']);

        $phnPublicHealthNursing = Unit::firstOrCreate([
            'name' => 'Public Health Nursing',
            'discipline_id' => $publicHealthNurses->id,
            'sort_order' => 1,
        ]);
        Subunit::firstOrCreate(['name' => 'CHPS', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Composite Public Health / N. P.', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'School Health', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Nutrition Promotion / Education', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Health Promotion / Education', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Environmental Health', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Community Rehabilitation', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Occupational Health', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Health Administration', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Nutrition & NCD Clinic', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'TB-DOT', 'unit_id' => $phnPublicHealthNursing->id, 'duration_weeks' => 3]);

        $phnMedicalNursing = Unit::firstOrCreate([
            'name' => 'Medical Nursing',
            'discipline_id' => $publicHealthNurses->id,
            'sort_order' => 2,
        ]);
        Subunit::firstOrCreate(['name' => 'Male Ward', 'unit_id' => $phnMedicalNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Female/Kids', 'unit_id' => $phnMedicalNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Emergency', 'unit_id' => $phnMedicalNursing->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'OPD', 'unit_id' => $phnMedicalNursing->id, 'duration_weeks' => 1]);

        $phnMCH = Unit::firstOrCreate([
            'name' => 'MCH',
            'discipline_id' => $publicHealthNurses->id,
            'sort_order' => 3,
        ]);
        Subunit::firstOrCreate(['name' => 'MCH', 'unit_id' => $phnMCH->id, 'duration_weeks' => 4]);

        $phnPsychiatry = Unit::firstOrCreate([
            'name' => 'Psychiatry (KPC)',
            'discipline_id' => $publicHealthNurses->id,
            'sort_order' => 4,
        ]);
        Subunit::firstOrCreate(['name' => 'Comm. Psychiatry', 'unit_id' => $phnPsychiatry->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Acute Ward', 'unit_id' => $phnPsychiatry->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'Chronic Ward', 'unit_id' => $phnPsychiatry->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'OPD', 'unit_id' => $phnPsychiatry->id, 'duration_weeks' => 1]);

        $phnSpecialClinic = Unit::firstOrCreate([
            'name' => 'Special Clinic',
            'discipline_id' => $publicHealthNurses->id,
            'sort_order' => 5,
        ]);
        Subunit::firstOrCreate(['name' => 'Chest Clinic', 'unit_id' => $phnSpecialClinic->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'ENT Clinic', 'unit_id' => $phnSpecialClinic->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Diabetic / Hypertensive', 'unit_id' => $phnSpecialClinic->id, 'duration_weeks' => 1]);
        Subunit::firstOrCreate(['name' => 'Eye Clinic', 'unit_id' => $phnSpecialClinic->id, 'duration_weeks' => 1]);

        $phnObstetricNursing = Unit::firstOrCreate([
            'name' => 'Obstetric Nursing',
            'discipline_id' => $publicHealthNurses->id,
            'sort_order' => 6,
        ]);
        Subunit::firstOrCreate(['name' => 'Labour/Delivery & Postnatal/Neonatal', 'unit_id' => $phnObstetricNursing->id, 'duration_weeks' => 7]);
        Subunit::firstOrCreate(['name' => 'Gynae (MCH)', 'unit_id' => $phnObstetricNursing->id, 'duration_weeks' => 2]);
        Subunit::firstOrCreate(['name' => 'ANC', 'unit_id' => $phnObstetricNursing->id, 'duration_weeks' => 2]);
    }
}