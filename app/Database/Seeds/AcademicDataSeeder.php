<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        // Seed Academic Years
        $academicYears = [
            [
                'year_start' => $currentYear - 1,
                'year_end' => $currentYear,
                'description' => ($currentYear - 1) . '-' . $currentYear,
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'year_start' => $currentYear,
                'year_end' => $nextYear,
                'description' => $currentYear . '-' . $nextYear,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'year_start' => $nextYear,
                'year_end' => $nextYear + 1,
                'description' => $nextYear . '-' . ($nextYear + 1),
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('academic_years')->insertBatch($academicYears);

        // Seed Year Levels
        $yearLevels = [
            [
                'level_name' => '1st Year',
                'level_number' => 1,
                'description' => 'First Year',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'level_name' => '2nd Year',
                'level_number' => 2,
                'description' => 'Second Year',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'level_name' => '3rd Year',
                'level_number' => 3,
                'description' => 'Third Year',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'level_name' => '4th Year',
                'level_number' => 4,
                'description' => 'Fourth Year',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('year_levels')->insertBatch($yearLevels);

        // Seed Semesters
        $semesters = [
            [
                'semester_name' => 'First Semester',
                'semester_number' => 1,
                'description' => 'First Semester',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'semester_name' => 'Second Semester',
                'semester_number' => 2,
                'description' => 'Second Semester',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('semesters')->insertBatch($semesters);

        // Seed Terms (3 terms + 1 summer)
        $terms = [
            [
                'term_name' => '1st Term',
                'term_number' => 1,
                'is_summer' => 0,
                'description' => 'First Term',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'term_name' => '2nd Term',
                'term_number' => 2,
                'is_summer' => 0,
                'description' => 'Second Term',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'term_name' => '3rd Term',
                'term_number' => 3,
                'is_summer' => 0,
                'description' => 'Third Term',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'term_name' => 'Summer',
                'term_number' => 4,
                'is_summer' => 1,
                'description' => 'Summer Term',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        $this->db->table('terms')->insertBatch($terms);

        echo "Academic data seeded successfully!\n";
        echo "- Academic Years: " . count($academicYears) . "\n";
        echo "- Year Levels: " . count($yearLevels) . "\n";
        echo "- Semesters: " . count($semesters) . "\n";
        echo "- Terms: " . count($terms) . " (3 terms + 1 summer)\n";
    }
}

