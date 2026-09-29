<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('???###')),
            'name' => fake()->randomElement([
                'Introduction to Programming', 'Data Structures and Algorithms',
                'Database Systems', 'Web Application Development', 'Computer Networks',
                'Operating Systems', 'Software Engineering', 'Discrete Mathematics',
            ]),
            'description' => fake()->sentence(12),
        ];
    }
}
