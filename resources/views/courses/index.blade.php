@extends('layout')

@section('title', 'Courses')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Courses</h1>
        <a href="{{ route('courses.create') }}" class="btn btn-primary">Add course</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th class="text-end">Students</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td class="fw-semibold">{{ $course->code }}</td>
                            <td><a href="{{ route('courses.show', $course) }}">{{ $course->name }}</a></td>
                            <td class="text-end">{{ $course->students_count }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @include('partials.delete-button', ['action' => route('courses.destroy', $course), 'label' => $course->code])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No courses yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $courses->links('pagination::bootstrap-5') }}</div>
@endsection
