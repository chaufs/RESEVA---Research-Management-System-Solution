@extends('layouts.student')

@section('title', 'My Classes')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">My Classes</h1>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    @if($classes->isEmpty())
        <div class="alert alert-info">No classes enrolled. Contact admin for enrollment.</div>
    @else
        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm hover-shadow">
                        <div class="card-body">
                            <h6 class="card-title mb-2">{{ $class->class_name }}</h6>
                            <p class="small text-muted mb-2">{{ $class->subject ?? 'No subject' }}</p>
                            <p class="small mb-3">Teacher: {{ trim(($class->teacher?->firstname ?? '') . ' ' . ($class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}</p>
                            <div class="mb-3">
                                <span class="badge bg-secondary me-1">{{ $class->tasks_count }} tasks</span>
                                <span class="badge bg-primary">Year {{ $class->year_level ?? 'N/A' }}</span>
                            </div>
                            <a href="{{ route('student.classes.show', $class) }}" class="btn btn-primary w-100">View Details & Tasks</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>
@endsection

