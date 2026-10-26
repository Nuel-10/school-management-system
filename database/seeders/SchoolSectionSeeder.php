<?php

namespace Database\Seeders;

use App\Models\SchoolSection;
use Illuminate\Database\Seeder;

class SchoolSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolSection::updateOrCreate(
            ['code' => 'FIRST'],
            [
                'name' => 'First School',
                'description' => 'Creche, Nursery and Primary school section.',
                'is_active' => true,
            ]
        );

        SchoolSection::updateOrCreate(
            ['code' => 'COLLEGE'],
            [
                'name' => 'College',
                'description' => 'College school section.',
                'is_active' => true,
            ]
        );
    }
}