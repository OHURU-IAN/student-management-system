@extends('layout')

@section('title', 'Add student')

@section('content')
    <h1 class="h3 mb-3">Add student</h1>
    <div class="card shadow-sm"><div class="card-body">
        <form method="POST" action="{{ route('students.store') }}">
            @include('students._form')
            <button class="btn btn-primary">Save student</button>
            <a href="{{ route('students.index') }}" class="btn btn-link">Cancel</a>
        </form>
    </div></div>
@endsection
