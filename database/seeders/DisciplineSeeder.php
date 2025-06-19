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
        $midwives = Discipline::create(['name' => 'Midwives']);

        // Public Health Unit (Midwives)
        $midwivesPublicHealth = Unit::create(['name' => 'Public Health', 'discipline_id' => $midwives->id]);
        Subunit::create(['name' => 'CWC', 'duration_weeks' => 2, 'unit_id' => $midwivesPublicHealth->id]);
        Subunit::create(['name' => 'Nutrition', 'duration_weeks' => 2, 'unit_id' => $midwivesPublicHealth->id]);
        Subunit::create(['name' => 'CHPS', 'duration_weeks' => 2, 'unit_id' => $midwivesPublicHealth->id]);
        Subunit::create(['name' => 'Home Visit', 'duration_weeks' => 2, 'unit_id' => $midwivesPublicHealth->id]);
        Subunit::create(['name' => 'Family Planning', 'duration_weeks' => 3, 'unit_id' => $midwivesPublicHealth->id]);

        // Surgical Nursing Unit (Midwives)
        $midwivesSurgicalNursing = Unit::create(['name' => 'Surgical Nursing', 'discipline_id' => $midwives->id]);
        Subunit::create(['name' => 'Eye', 'duration_weeks' => 1, 'unit_id' => $midwivesSurgicalNursing->id]);
        Subunit::create(['name' => 'ENT', 'duration_weeks' => 1, 'unit_id' => $midwivesSurgicalNursing->id]);
        Subunit::create(['name' => 'MCH', 'duration_weeks' => 2, 'unit_id' => $midwivesSurgicalNursing->id]);

        // Maternity Unit (Midwives)
        $midwivesMaternity = Unit::create(['name' => 'Maternity', 'discipline_id' => $midwives->id]);
        Subunit::create(['name' => 'ANC', 'duration_weeks' => 5, 'unit_id' => $midwivesMaternity->id]);
        Subunit::create(['name' => 'Labour/Delivery & Postnatal Care', 'duration_weeks' => 17, 'unit_id' => $midwivesMaternity->id]);
        Subunit::create(['name' => 'Neonatal Care (KPC)', 'duration_weeks' => 5, 'unit_id' => $midwivesMaternity->id]);
        Subunit::create(['name' => 'Gynae (MCH)', 'duration_weeks' => 1, 'unit_id' => $midwivesMaternity->id]);

        // Medical Nursing Unit (Midwives)
        $midwivesMedicalNursing = Unit::create(['name' => 'Medical Nursing', 'discipline_id' => $midwives->id]);
        Subunit::create(['name' => 'Male Ward', 'duration_weeks' => 2, 'unit_id' => $midwivesMedicalNursing->id]);
        Subunit::create(['name' => 'Female/Kids', 'duration_weeks' => 1, 'unit_id' => $midwivesMedicalNursing->id]);
        Subunit::create(['name' => 'Emergency', 'duration_weeks' => 1, 'unit_id' => $midwivesMedicalNursing->id]);
        Subunit::create(['name' => 'OPD', 'duration_weeks' => 1, 'unit_id' => $midwivesMedicalNursing->id]);

        // Psychiatry (KPC) Unit (Midwives)
        $midwivesPsychiatry = Unit::create(['name' => 'Psychiatry (KPC)', 'discipline_id' => $midwives->id]);
        Subunit::create(['name' => 'Comm. Psychiatry', 'duration_weeks' => 4, 'unit_id' => $midwivesPsychiatry->id]);


        

        // Seed RGN Discipline
        $rgn = Discipline::create(['name' => 'Registered General Nurses (RGN)']);

        // Medical Nursing Unit (RGN)
        $rgnMedicalNursing = Unit::create(['name' => 'Medical Nursing', 'discipline_id' => $rgn->id]);
        Subunit::create(['name' => 'OPD', 'duration_weeks' => 12, 'unit_id' => $rgnMedicalNursing->id]);
        Subunit::create(['name' => 'Female Ward / Paediatric Ward', 'duration_weeks' => 12, 'unit_id' => $rgnMedicalNursing->id]);
        Subunit::create(['name' => 'Male Ward', 'duration_weeks' => 12, 'unit_id' => $rgnMedicalNursing->id]);
        Subunit::create(['name' => 'Emergency', 'duration_weeks' => 12, 'unit_id' => $rgnMedicalNursing->id]);

        // Surgical Nursing Unit (RGN)
        $rgnSurgicalNursing = Unit::create(['name' => 'Surgical Nursing', 'discipline_id' => $rgn->id]);
        Subunit::create(['name' => 'MCH', 'duration_weeks' => 5, 'unit_id' => $rgnSurgicalNursing->id]);
        Subunit::create(['name' => 'MCH (continued)', 'duration_weeks' => 5, 'unit_id' => $rgnSurgicalNursing->id]);

        // Obstetrics Nursing Unit (RGN)
        $rgnObstetricsNursing = Unit::create(['name' => 'Obstetrics Nursing', 'discipline_id' => $rgn->id]);
        Subunit::create(['name' => 'ANC (Antenatal Care)', 'duration_weeks' => 4, 'unit_id' => $rgnObstetricsNursing->id]);

        // Public Health Unit (RGN)
        $rgnPublicHealth = Unit::create(['name' => 'Public Health', 'discipline_id' => $rgn->id]);
        Subunit::create(['name' => 'Health Promotion/Education', 'duration_weeks' => 2, 'unit_id' => $rgnPublicHealth->id]);
        Subunit::create(['name' => 'Home Visit', 'duration_weeks' => 2, 'unit_id' => $rgnPublicHealth->id]);
        Subunit::create(['name' => 'Family Planning', 'duration_weeks' => 2, 'unit_id' => $rgnPublicHealth->id]);
        Subunit::create(['name' => 'School Health', 'duration_weeks' => 2, 'unit_id' => $rgnPublicHealth->id]);
        Subunit::create(['name' => 'CWC (Child Welfare Clinic)', 'duration_weeks' => 2, 'unit_id' => $rgnPublicHealth->id]);

        // Psychiatry Unit (RGN)
        $rgnPsychiatry = Unit::create(['name' => 'Psychiatry', 'discipline_id' => $rgn->id]);
        Subunit::create(['name' => 'Community Psychiatry (KPC)', 'duration_weeks' => 3, 'unit_id' => $rgnPsychiatry->id]);
        Subunit::create(['name' => 'Acute Ward / Chronic Ward (Outside)', 'duration_weeks' => 3, 'unit_id' => $rgnPsychiatry->id]);
        Subunit::create(['name' => 'Same or related rotations', 'duration_weeks' => 3, 'unit_id' => $rgnPsychiatry->id]);
        Subunit::create(['name' => 'Same or related rotations', 'duration_weeks' => 3, 'unit_id' => $rgnPsychiatry->id]);

        // Special Clinic Unit (RGN)
        $rgnSpecialClinic = Unit::create(['name' => 'Special Clinic', 'discipline_id' => $rgn->id]);
        Subunit::create(['name' => 'ENT (Ear, Nose, Throat)', 'duration_weeks' => 2, 'unit_id' => $rgnSpecialClinic->id]);
        Subunit::create(['name' => 'Eye', 'duration_weeks' => 2, 'unit_id' => $rgnSpecialClinic->id]);
        Subunit::create(['name' => 'HIV/ART/CT', 'duration_weeks' => 2, 'unit_id' => $rgnSpecialClinic->id]);
        Subunit::create(['name' => 'Diabetic / Hypertensive', 'duration_weeks' => 2, 'unit_id' => $rgnSpecialClinic->id]);

        // Seed Public Health Nurses Discipline
        $publicHealthNurses = Discipline::create(['name' => 'Public Health Nurses']);

        // Public Health Nursing Unit (Public Health Nurses)
        $phnPublicHealthNursing = Unit::create(['name' => 'Public Health Nursing', 'discipline_id' => $publicHealthNurses->id]);
        Subunit::create(['name' => 'CHPS', 'duration_weeks' => 2, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Composite Public Health / N. P.', 'duration_weeks' => 1, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'School Health', 'duration_weeks' => 1, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Nutrition Promotion / Education', 'duration_weeks' => 1, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Health Promotion / Education', 'duration_weeks' => 2, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Environmental Health', 'duration_weeks' => 2, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Community Rehabilitation', 'duration_weeks' => 1, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Occupational Health', 'duration_weeks' => 1, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Health Administration', 'duration_weeks' => 1, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'Nutrition & NCD Clinic', 'duration_weeks' => 2, 'unit_id' => $phnPublicHealthNursing->id]);
        Subunit::create(['name' => 'TB-DOT', 'duration_weeks' => 3, 'unit_id' => $phnPublicHealthNursing->id]);

        // Medical Nursing Unit (Public Health Nurses)
        $phnMedicalNursing = Unit::create(['name' => 'Medical Nursing', 'discipline_id' => $publicHealthNurses->id]);
        Subunit::create(['name' => 'Male Ward', 'duration_weeks' => 2, 'unit_id' => $phnMedicalNursing->id]);
        Subunit::create(['name' => 'Female/Kids', 'duration_weeks' => 1, 'unit_id' => $phnMedicalNursing->id]);
        Subunit::create(['name' => 'Emergency', 'duration_weeks' => 1, 'unit_id' => $phnMedicalNursing->id]);
        Subunit::create(['name' => 'OPD', 'duration_weeks' => 1, 'unit_id' => $phnMedicalNursing->id]);

        // MCH Unit (Public Health Nurses)
        $phnMCH = Unit::create(['name' => 'MCH', 'discipline_id' => $publicHealthNurses->id]);
        Subunit::create(['name' => 'MCH', 'duration_weeks' => 4, 'unit_id' => $phnMCH->id]);

        // Psychiatry (KPC) Unit (Public Health Nurses)
        $phnPsychiatry = Unit::create(['name' => 'Psychiatry (KPC)', 'discipline_id' => $publicHealthNurses->id]);
        Subunit::create(['name' => 'Comm. Psychiatry', 'duration_weeks' => 2, 'unit_id' => $phnPsychiatry->id]);
        Subunit::create(['name' => 'Acute Ward', 'duration_weeks' => 2, 'unit_id' => $phnPsychiatry->id]);
        Subunit::create(['name' => 'Chronic Ward', 'duration_weeks' => 1, 'unit_id' => $phnPsychiatry->id]);
        Subunit::create(['name' => 'OPD', 'duration_weeks' => 1, 'unit_id' => $phnPsychiatry->id]);

        // Special Clinic Unit (Public Health Nurses)
        $phnSpecialClinic = Unit::create(['name' => 'Special Clinic', 'discipline_id' => $publicHealthNurses->id]);
        Subunit::create(['name' => 'Chest Clinic', 'duration_weeks' => 2, 'unit_id' => $phnSpecialClinic->id]);
        Subunit::create(['name' => 'ENT Clinic', 'duration_weeks' => 1, 'unit_id' => $phnSpecialClinic->id]);
        Subunit::create(['name' => 'Diabetic / Hypertensive', 'duration_weeks' => 1, 'unit_id' => $phnSpecialClinic->id]);
        Subunit::create(['name' => 'Eye Clinic', 'duration_weeks' => 1, 'unit_id' => $phnSpecialClinic->id]);

        // Obstetric Nursing Unit (Public Health Nurses)
        $phnObstetricNursing = Unit::create(['name' => 'Obstetric Nursing', 'discipline_id' => $publicHealthNurses->id]);
        Subunit::create(['name' => 'Labour/Delivery & Postnatal/Neonatal', 'duration_weeks' => 7, 'unit_id' => $phnObstetricNursing->id]);
        Subunit::create(['name' => 'Gynae (MCH)', 'duration_weeks' => 2, 'unit_id' => $phnObstetricNursing->id]);
        Subunit::create(['name' => 'ANC', 'duration_weeks' => 2, 'unit_id' => $phnObstetricNursing->id]);
    }
}