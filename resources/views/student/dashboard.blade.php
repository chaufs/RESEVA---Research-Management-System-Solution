@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="container">
    <div class="student-hero p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-start gap-4">
            <div class="flex-grow-1">
                <span class="badge student-badge rounded-pill mb-3 px-3 py-2">Student Portal</span>
                <h1 class="display-6 fw-bold mb-2">Welcome, {{ $student->SFname }} {{ $student->SLname }}</h1>
                <p class="text-muted mb-0 fs-5">Review your recent activities, track class tasks, and keep your submissions organized in one place.</p>
            </div>

            
        </div>
    </div>

    <!-- Removed profile and nested rectangles for a cleaner UI -->

    <ul class="nav nav-pills mb-4 gap-2" id="studentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab">Recent Activities</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab">My Classes</button>
        </li>
    </ul>

    <div class="tab-content" id="studentTabContent">
        <div class="tab-pane fade show active" id="recent" role="tabpanel">
            <div class="card student-card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 student-section-title">Recent Tasks</h5>
                    <span class="badge student-badge">{{ $recentTasks->count() }} items</span>
                </div>
                <div class="card-body">
                    @if($recentTasks->isEmpty())
                        <div class="student-empty-state text-center">
                            <h6 class="mb-2">No recent tasks yet</h6>
                            <p class="text-muted mb-0">Check your classes for new assignments and updates from your teacher.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($recentTasks as $task)
                                <div class="col-12">
                                    <a href="{{ route('student.classes.show', $task->class) }}" class="text-decoration-none stretched-link">
                                        <div class="card student-card position-relative hover-shadow" style="cursor:pointer;">
                                            <div class="card-body">
                                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                            <span class="badge student-badge">Task</span>
                                                            <span class="text-muted small">{{ $task->class->class_name }}</span>
                                                        </div>
                                                        <h5 class="mb-2">{{ $task->title }}</h5>
                                                        <p class="text-muted mb-2 small">Teacher: {{ trim(($task->class->teacher?->firstname ?? '') . ' ' . ($task->class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}</p>
                                                        @if($task->description)
                                                            <p class="mb-0">{{ Str::limit($task->description, 130) }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-md-end">
                                                        @if($task->due_date)
                                                            <span class="badge {{ $task->due_date->isPast() ? 'text-bg-danger' : 'text-bg-warning' }} px-3 py-2">
                                                                Due {{ $task->due_date->format('M d') }}
                                                            </span>
                                                        @else
                                                            <span class="badge text-bg-secondary px-3 py-2">No due date</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="classes" role="tabpanel">
            @if($classes->isEmpty())
                <div class="student-empty-state text-center">
                    <h5 class="mb-2">No classes enrolled yet</h5>
                    <p class="text-muted mb-0">Contact your administrator or teacher to be added to a class.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($classes as $class)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('student.classes.show', $class) }}" class="text-decoration-none stretched-link">
                                <div class="card student-card h-100 overflow-hidden position-relative hover-shadow" style="cursor:pointer;">
                                    <div class="card-body">
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
                                            <span class="badge text-bg-light border">{{ $class->tasks->count() ?? 0 }} tasks</span>
                                            <span class="badge text-bg-light border">{{ $class->program?->program_name ?? 'No program' }}</span>
                                        </div>
                                        <span class="visually-hidden">View Class</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<style>
.hover-shadow:hover {
    box-shadow: 0 8px 32px rgba(52,88,255,0.13) !important;
    transform: translateY(-2px) scale(1.01);
    transition: box-shadow 0.18s, transform 0.18s;
}
.stretched-link {
    position: static;
}
</style>
@endsection
