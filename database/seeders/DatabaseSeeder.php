<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample courses and students.
     */
    public function run(): void
    {
        // Demo login for local development only. Create real accounts with: php artisan user:create
        if (app()->environment('local')) {
            User::firstOrCreate(
                ['email' => 'admin@example.com'],
                ['name' => 'Demo Admin', 'password' => 'password'],
            );
        }

        $courses = [
            ['code' => 'CS101', 'name' => 'Introduction to Programming', 'description' => 'Programming fundamentals: variables, control flow, functions and basic data structures.'],
            ['code' => 'CS204', 'name' => 'Data Structures and Algorithms', 'description' => 'Lists, trees, graphs and hash tables, with analysis of algorithm complexity.'],
            ['code' => 'IS210', 'name' => 'Database Systems', 'description' => 'Relational modelling, SQL, normalisation and transactions.'],
            ['code' => 'SE301', 'name' => 'Web Application Development', 'description' => 'Building server-rendered and API-driven web applications end to end.'],
        ];

        foreach ($courses as $course) {
            Course::create($course)->students()->saveMany(Student::factory()->count(8)->make(['course_id' => null]));
        }

        Student::factory()->count(3)->create(['course_id' => null]);
    }
}
