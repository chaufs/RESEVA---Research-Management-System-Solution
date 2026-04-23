@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Management</h1>
    <div class="mb-3">
        <a href="{{ route('user-management.create-teacher') }}" class="btn btn-primary">Create Teacher</a>
        <a href="{{ route('user-management.create-student') }}" class="btn btn-secondary">Create Student</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <!-- Add list of users here if needed -->
</div>
@endsection