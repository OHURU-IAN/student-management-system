<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * List students, optionally filtered by a search term and course.
     */
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with('course')
            ->search($request->string('q')->trim()->value())
            ->when($request->integer('course'), fn ($q, $courseId) => $q->where('course_id', $courseId))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', [
            'students' => $students,
            'courses' => Course::orderBy('name')->get(['id', 'code', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('students.create', [
            'student' => new Student,
            'courses' => Course::orderBy('name')->get(['id', 'code', 'name']),
        ]);
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $student = Student::create($request->validated());

        return redirect()->route('students.show', $student)
            ->with('status', "Student {$student->name} was added.");
    }

    public function show(Student $student): View
    {
        return view('students.show', ['student' => $student->load('course')]);
    }

    public function edit(Student $student): View
    {
        return view('students.edit', [
            'student' => $student,
            'courses' => Course::orderBy('name')->get(['id', 'code', 'name']),
        ]);
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()->route('students.show', $student)
            ->with('status', 'Student details were updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('status', "Student {$student->name} was deleted.");
    }
}
