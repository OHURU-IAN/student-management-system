@extends('layout')

@section('title', 'Edit '.$student->name)

@section('content')
    <h1 class="h3 mb-3">Edit {{ $student->name }}</h1>
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('students.update', $student) }}">
            @method('PUT')
            @include('students._form')
            <button class="btn btn-primary">Save changes</button>
            <a href="{{ route('students.show', $student) }}" class="btn btn-link">Cancel</a>
        </form>
    </div></div>
@endsection
