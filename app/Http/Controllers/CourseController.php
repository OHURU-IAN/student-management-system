<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        return view('courses.index', [
            'courses' => Course::withCount('students')->orderBy('name')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('courses.create', ['course' => new Course]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $course = Course::create($request->validated());

        return redirect()->route('courses.show', $course)
            ->with('status', "Course {$course->code} was added.");
    }

    public function show(Course $course): View
    {
        return view('courses.show', [
            'course' => $course,
            'students' => $course->students()->orderBy('name')->paginate(10),
        ]);
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', ['course' => $course]);
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('courses.show', $course)
            ->with('status', 'Course details were updated.');
    }

    /**
     * Delete a course. Its students stay on record, unassigned (nullOnDelete).
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('status', "Course {$course->code} was deleted. Its students are now unassigned.");
    }
}
