@extends('layout')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">Students</div>
                <div class="display-6">{{ $studentCount }}</div>
                <a href="{{ route('students.index') }}" class="stretched-link"><span class="visually-hidden">View students</span></a>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">Courses</div>
                <div class="display-6">{{ $courseCount }}</div>
                <a href="{{ route('courses.index') }}" class="stretched-link"><span class="visually-hidden">View courses</span></a>
            </div></div>
        </div>
        <div class="col-sm-4">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">Not assigned to a course</div>
                <div class="display-6">{{ $unassignedCount }}</div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Recently added students</span>
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">Add student</a>
        </div>
        <ul class="list-group list-group-flush">
            @forelse ($recentStudents as $student)
                <li class="list-group-item d-flex justify-content-between">
                    <a href="{{ route('students.show', $student) }}">{{ $student->name }}</a>
                    <span class="text-muted small">{{ $student->course?->code ?? 'Unassigned' }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No students yet.</li>
            @endforelse
        </ul>
    </div>
@endsection
