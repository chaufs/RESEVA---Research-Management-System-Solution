@extends('layouts.app')

@section('title', 'Create Student')

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1>Create Student</h1>
            <p class="text-muted mb-0">Add a new student account and assign it to the correct program.</p>
        </div>
        <a href="{{ route('user-management.index') }}" class="btn btn-outline-primary">Back</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user-management.store-student') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label for="SFname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="SFname" name="SFname" required>
        </div>
        <div class="mb-3">
            <label for="SMname" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="SMname" name="SMname">
        </div>
        <div class="mb-3">
            <label for="SLname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="SLname" name="SLname" required>
        </div>
        <div class="mb-3">
            <label for="program_id" class="form-label">Program</label>
            <select class="form-control" id="program_id" name="program_id" required>
                <option value="">Select Program</option>
                @foreach($programs as $program)
                    <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Student</button>
    </form>
</div>
@endsection