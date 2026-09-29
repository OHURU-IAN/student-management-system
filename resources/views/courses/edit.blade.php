@extends('layout')

@section('title', 'Edit '.$course->code)

@section('content')
    <h1 class="h3 mb-3">Edit {{ $course->code }}</h1>
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('courses.update', $course) }}">
            @method('PUT')
            @include('courses._form')
            <button class="btn btn-primary">Save changes</button>
            <a href="{{ route('courses.show', $course) }}" class="btn btn-link">Cancel</a>
        </form>
    </div></div>
@endsection
