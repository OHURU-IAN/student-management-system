@extends('layout')

@section('title', $student->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $student->name }}</h1>
        <div class="text-nowrap">
            <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary">Edit</a>
            @include('partials.delete-button', ['action' => route('students.destroy', $student), 'label' => $student->name, 'size' => ''])
        </div>
    </div>

    <div class="card shadow-sm"><div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9">{{ $student->email ?? '-' }}</dd>
            <dt class="col-sm-3">Mobile</dt>
            <dd class="col-sm-9">{{ $student->mobile }}</dd>
            <dt class="col-sm-3">Address</dt>
            <dd class="col-sm-9">{{ $student->address }}</dd>
            <dt class="col-sm-3">Course</dt>
            <dd class="col-sm-9">
                @if ($student->course)
                    <a href="{{ route('courses.show', $student->course) }}">{{ $student->course->code }} - {{ $student->course->name }}</a>
                @else
                    Unassigned
                @endif
            </dd>
            <dt class="col-sm-3">Added</dt>
            <dd class="col-sm-9 mb-0">{{ $student->created_at->format('j M Y') }}</dd>
        </dl>
    </div></div>

    <a href="{{ route('students.index') }}" class="btn btn-link px-0 mt-3">&larr; All students</a>
@endsection
