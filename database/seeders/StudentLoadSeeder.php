<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Section;
use App\Models\Kindergarten;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentLoadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generates N students across existing sections to test write load.
     *
     * Usage: php artisan db:seed --class=StudentLoadSeeder
     */
    public function run(): void
    {
        $count = (int) env('STUDENT_LOAD_COUNT', 200);

        $sections = Section::pluck('id')->all();
        $kindergartenIds = Kindergarten::pluck('id')->all();

        if (empty($sections) || empty($kindergartenIds)) {
            $this->command->info('No sections or kindergartens found. Run main seeder first.');
            return;
        }

        $faker = \Faker\Factory::create('ar_SA');

        $this->command->info("Generating {$count} students...");

        $batch = [];
        for ($i = 0; $i < $count; $i++) {
            $kindergartenId = $faker->randomElement($kindergartenIds);
            $sectionId = $faker->randomElement($sections);

            $first = $faker->firstName;
            $father = $faker->firstName;
            $grand = $faker->firstName;
            $family = $faker->lastName;

            $registration = date('Y') . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $batch[] = [
                'kindergarten_id' => $kindergartenId,
                'section_id' => $sectionId,
                'first_name' => $first,
                'father_name' => $father,
                'grandfather_name' => $grand,
                'family_name' => $family,
                'mother_name' => $faker->firstNameFemale,
                'birth_date' => $faker->date('Y-m-d', '2019-01-01'),
                'siblings_count' => $faker->numberBetween(0,4),
                'birth_order' => $faker->numberBetween(1,5),
                'registration_number' => $registration,
                'nationality' => $faker->country,
                'address' => $faker->address,
                'father_marital_status' => 'متزوج',
                'mother_marital_status' => 'متزوجة',
                'student_national_id' => null,
                'father_national_id' => null,
                'needs_transportation' => false,
                'needs_bathroom_care' => false,
                'father_phone' => $faker->numerify('09########'),
                'mother_phone' => $faker->numerify('09########'),
                'home_phone' => null,
                'status' => 'active',
                'academic_year' => '2024-2025',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // insert in chunks
            if (count($batch) >= 50) {
                DB::table('students')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('students')->insert($batch);
        }

        $this->command->info('Done.');
    }
}
