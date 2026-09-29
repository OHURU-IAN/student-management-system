<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Amina Wanjiru',
            'email' => 'amina@example.com',
            'address' => '12 Ngong Road, Nairobi',
            'mobile' => '+254 712 345 678',
            'course_id' => null,
        ], $overrides);
    }

    public function test_dashboard_shows_counts(): void
    {
        Student::factory()->count(2)->create();
        Student::factory()->create(['course_id' => null]);

        $this->get('/')
            ->assertOk()
            ->assertViewHas('studentCount', 3)
            ->assertViewHas('unassignedCount', 1);
    }

    public function test_index_lists_students_with_their_course(): void
    {
        $student = Student::factory()->create();

        $this->get(route('students.index'))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee($student->course->code);
    }

    public function test_index_searches_by_name_email_or_mobile(): void
    {
        Student::factory()->create(['name' => 'Brian Otieno', 'mobile' => '0700000001']);
        Student::factory()->create(['name' => 'Cynthia Achieng', 'email' => 'cynthia@school.test']);

        $this->get(route('students.index', ['q' => 'Brian']))
            ->assertSee('Brian Otieno')->assertDontSee('Cynthia Achieng');

        $this->get(route('students.index', ['q' => 'school.test']))
            ->assertSee('Cynthia Achieng')->assertDontSee('Brian Otieno');

        $this->get(route('students.index', ['q' => '0700000001']))
            ->assertSee('Brian Otieno')->assertDontSee('Cynthia Achieng');
    }

    public function test_index_filters_by_course(): void
    {
        $inCourse = Student::factory()->create();
        $other = Student::factory()->create();

        $this->get(route('students.index', ['course' => $inCourse->course_id]))
            ->assertViewHas('students', fn ($page) => $page->pluck('id')->all() === [$inCourse->id]);
    }

    public function test_index_paginates(): void
    {
        Student::factory()->count(12)->create();

        $this->get(route('students.index'))
            ->assertViewHas('students', fn ($page) => $page->count() === 10 && $page->total() === 12);
    }

    public function test_can_create_a_student(): void
    {
        $course = Course::factory()->create();

        $response = $this->post(route('students.store'), $this->validData(['course_id' => $course->id]));

        $student = Student::sole();
        $response->assertRedirect(route('students.show', $student))->assertSessionHas('status');
        $this->assertSame('Amina Wanjiru', $student->name);
        $this->assertTrue($student->course->is($course));
    }

    public function test_create_validates_input(): void
    {
        $this->post(route('students.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'address' => '',
            'mobile' => 'call me',
            'course_id' => 999,
        ])->assertSessionHasErrors(['name', 'email', 'address', 'mobile', 'course_id']);

        $this->assertDatabaseCount('students', 0);
    }

    public function test_email_must_be_unique(): void
    {
        Student::factory()->create(['email' => 'taken@example.com']);

        $this->post(route('students.store'), $this->validData(['email' => 'taken@example.com']))
            ->assertSessionHasErrors('email');
    }

    public function test_can_view_a_student(): void
    {
        $student = Student::factory()->create();

        $this->get(route('students.show', $student))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee($student->address);
    }

    public function test_can_update_a_student_keeping_the_same_email(): void
    {
        $student = Student::factory()->create(['email' => 'same@example.com']);

        $this->put(route('students.update', $student), $this->validData([
            'name' => 'Renamed Student',
            'email' => 'same@example.com',
        ]))->assertRedirect(route('students.show', $student));

        $this->assertSame('Renamed Student', $student->fresh()->name);
        $this->assertNull($student->fresh()->course_id);
    }

    public function test_edit_form_is_prefilled(): void
    {
        $student = Student::factory()->create();

        $this->get(route('students.edit', $student))
            ->assertOk()
            ->assertSee('value="'.e($student->mobile).'"', false);
    }

    public function test_can_delete_a_student(): void
    {
        $student = Student::factory()->create();

        $this->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));

        $this->assertModelMissing($student);
    }

    public function test_missing_student_returns_404(): void
    {
        $this->get(route('students.show', 12345))->assertNotFound();
    }
}
