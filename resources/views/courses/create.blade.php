@extends('layout')

@section('title', 'Add course')

@section('content')
    <h1 class="h3 mb-3">Add course</h1>
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('courses.store') }}">
            @include('courses._form')
            <button class="btn btn-primary">Save course</button>
            <a href="{{ route('courses.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div></div>
@endsection
