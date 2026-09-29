@extends('layout')

@section('title', $course->code)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="text-muted small">{{ $course->code }}</div>
            <h1 class="h3 mb-0">{{ $course->name }}</h1>
        </div>
        <div class="text-nowrap">
            <a href="{{ route('courses.edit', $course) }}" class="btn btn-outline-primary">Edit</a>
            @include('partials.delete-button', ['action' => route('courses.destroy', $course), 'label' => $course->code, 'size' => ''])
        </div>
    </div>

    @if ($course->description)
        <p class="text-muted">{{ $course->description }}</p>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Enrolled students ({{ $students->total() }})</div>
        <ul class="list-group list-group-flush">
            @forelse ($students as $student)
                <li class="list-group-item d-flex justify-content-between">
                    <a href="{{ route('students.show', $student) }}">{{ $student->name }}</a>
                    <span class="text-muted small">{{ $student->mobile }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No students in this course yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-3">{{ $students->links('pagination::bootstrap-5') }}</div>

    <a href="{{ route('courses.index') }}" class="btn btn-link px-0 mt-2">&larr; All courses</a>
@endsection
