<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_courses_with_student_counts(): void
    {
        $course = Course::factory()->has(Student::factory()->count(3))->create();

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee($course->name)
            ->assertViewHas('courses', fn ($page) => $page->first()->students_count === 3);
    }

    public function test_can_create_a_course_and_code_is_upper_cased(): void
    {
        $this->post(route('courses.store'), [
            'code' => ' cs101 ',
            'name' => 'Introduction to Programming',
        ])->assertRedirect();

        $this->assertDatabaseHas('courses', ['code' => 'CS101', 'name' => 'Introduction to Programming']);
    }

    public function test_course_code_is_required_and_unique(): void
    {
        Course::factory()->create(['code' => 'CS101']);

        $this->post(route('courses.store'), ['code' => 'cs101', 'name' => 'Duplicate'])
            ->assertSessionHasErrors('code');

        $this->post(route('courses.store'), ['code' => '', 'name' => ''])
            ->assertSessionHasErrors(['code', 'name']);
    }

    public function test_show_lists_enrolled_students(): void
    {
        $course = Course::factory()->create();
        $enrolled = Student::factory()->for($course)->create();
        $other = Student::factory()->create();

        $this->get(route('courses.show', $course))
            ->assertOk()
            ->assertViewHas('students', fn ($page) => $page->pluck('id')->all() === [$enrolled->id]);
    }

    public function test_can_update_a_course_keeping_its_code(): void
    {
        $course = Course::factory()->create(['code' => 'SE200']);

        $this->put(route('courses.update', $course), ['code' => 'SE200', 'name' => 'Software Engineering'])
            ->assertRedirect(route('courses.show', $course));

        $this->assertSame('Software Engineering', $course->fresh()->name);
    }

    public function test_deleting_a_course_keeps_its_students_unassigned(): void
    {
        $course = Course::factory()->create();
        $student = Student::factory()->for($course)->create();

        $this->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'));

        $this->assertModelMissing($course);
        $this->assertModelExists($student);
        $this->assertNull($student->fresh()->course_id);
    }
}
