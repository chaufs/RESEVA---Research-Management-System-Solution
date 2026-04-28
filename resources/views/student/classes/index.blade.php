@extends('layouts.student')

@section('title', 'My Classes')

@section('content')
<div class="container">
    <div class="student-hero p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-4">
            <div>
                <span class="badge student-badge rounded-pill mb-3 px-3 py-2">My Classes</span>
                <h1 class="display-6 fw-bold mb-2">Your enrolled classes</h1>
                <p class="text-muted mb-0 fs-5">Open a class to view its tasks, details, and submissions.</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>
    </div>

    @if($classes->isEmpty())
        <div class="student-empty-state text-center">
            <h5 class="mb-2">No classes enrolled yet</h5>
            <p class="text-muted mb-0">Contact your administrator or teacher to be added to a class.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-xl-4">
                    <div class="card student-card h-100 overflow-hidden">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">{{ $class->class_name }}</h5>
                                    <p class="text-muted mb-0 small">{{ $class->subject ?? 'No subject assigned' }}</p>
                                </div>
                                <span class="badge student-badge">Year {{ $class->year_level ?? 'N/A' }}</span>
                            </div>

                            <p class="small text-muted mb-3">
                                Teacher: {{ trim(($class->teacher?->firstname ?? '') . ' ' . ($class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}
                            </p>

                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <span class="badge text-bg-light border">{{ $class->tasks_count }} tasks</span>
                                <span class="badge text-bg-light border">{{ $class->program?->program_name ?? 'No program' }}</span>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('student.classes.show', $class) }}" class="btn btn-primary w-100">View Details & Tasks</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
