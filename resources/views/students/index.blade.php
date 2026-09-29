@extends('layout')

@section('title', 'Students')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Students</h1>
        <a href="{{ route('students.create') }}" class="btn btn-primary">Add student</a>
    </div>

    <form method="GET" action="{{ route('students.index') }}" class="row g-2 mb-3" role="search">
        <div class="col-md-6">
            <label for="q" class="visually-hidden">Search</label>
            <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by name, email or mobile">
        </div>
        <div class="col-md-4">
            <label for="course" class="visually-hidden">Course</label>
            <select id="course" name="course" class="form-select">
                <option value="">All courses</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(request('course') == $course->id)>{{ $course->code }} - {{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-outline-secondary">Filter</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Course</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td><a href="{{ route('students.show', $student) }}">{{ $student->name }}</a></td>
                            <td>{{ $student->email ?? '-' }}</td>
                            <td>{{ $student->mobile }}</td>
                            <td>{{ $student->course?->code ?? 'Unassigned' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @include('partials.delete-button', ['action' => route('students.destroy', $student), 'label' => $student->name])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $students->links('pagination::bootstrap-5') }}</div>
@endsection
