@extends('layouts.student')

@section('title', $class->class_name)

@section('content')
<div class="container">
    <div class="student-hero p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-4">
            <div>
                <span class="badge student-badge rounded-pill mb-3 px-3 py-2">Class Details</span>
                <h1 class="display-6 fw-bold mb-2">{{ $class->class_name }}</h1>
                <p class="text-muted mb-0 fs-5">{{ $class->subject ?? 'No subject assigned' }} · Year {{ $class->year_level ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('student.classes') }}" class="btn btn-outline-secondary">← Back to Classes</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card student-card h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0 student-section-title">Class Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="student-card p-3 bg-white h-100">
                                <div class="text-muted small mb-1">Teacher</div>
                                <div class="fw-semibold">{{ trim(($class->teacher?->firstname ?? '') . ' ' . ($class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="student-card p-3 bg-white h-100">
                                <div class="text-muted small mb-1">Program</div>
                                <div class="fw-semibold">{{ $class->program?->program_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="student-card p-3 bg-white h-100">
                                <div class="text-muted small mb-1">Status</div>
                                <div>
                                    <span class="badge {{ strtolower($class->status) === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        {{ ucfirst($class->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="student-card p-3 bg-white h-100">
                                <div class="text-muted small mb-1">Available Tasks</div>
                                <div class="fw-bold h4 mb-0">{{ $class->tasks->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card student-card h-100">
                <div class="card-header py-3">
                    <h5 class="mb-0 student-section-title">At a Glance</h5>
                </div>
                <div class="card-body">
                    <div class="student-empty-state mb-3">
                        <div class="text-muted small mb-1">Class</div>
                        <div class="fw-bold">{{ $class->class_name }}</div>
                    </div>
                    <div class="student-empty-state mb-3">
                        <div class="text-muted small mb-1">Teacher</div>
                        <div class="fw-bold">{{ trim(($class->teacher?->firstname ?? '') . ' ' . ($class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}</div>
                    </div>
                    <div class="student-empty-state">
                        <div class="text-muted small mb-1">Program</div>
                        <div class="fw-bold">{{ $class->program?->program_name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card student-card">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 student-section-title">Assigned Tasks</h5>
            <span class="badge student-badge">{{ $tasksByGroup->count() }} groups</span>
        </div>
        <div class="card-body">
            @if($tasksByGroup->isEmpty())
                <div class="student-empty-state text-center">
                    <h5 class="mb-2">No tasks assigned yet</h5>
                    <p class="text-muted mb-0">Your teacher has not posted any tasks for this class.</p>
                </div>
            @else
                <div class="d-grid gap-4">
                    @foreach($tasksByGroup as $groupId => $tasks)
                        @php
                            $groupName = $tasks->first()?->researchGroup?->Group_Name ?? 'Ungrouped';
                        @endphp

                        <div class="student-card bg-white p-3 p-md-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <span class="badge student-badge mb-2">Group {{ $groupName }}</span>
                                    <h5 class="mb-1">{{ $tasks->count() }} task{{ $tasks->count() === 1 ? '' : 's' }}</h5>
                                    <p class="text-muted mb-0 small">Tasks assigned specifically for this group.</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                @foreach($tasks as $task)
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card student-card h-100">
                                            <div class="card-body d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge text-bg-light border">Task</span>
                                                    @if($task->due_date)
                                                        <span class="badge {{ $task->due_date->isPast() ? 'text-bg-danger' : 'text-bg-warning' }}">
                                                            {{ $task->due_date->format('M d') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h5 class="card-title mb-2">{{ $task->title }}</h5>
                                                <p class="text-muted small mb-3 flex-grow-1">
                                                    {{ $task->description ? Str::limit($task->description, 110) : 'No description provided.' }}
                                                </p>

                                                <div class="mt-auto">
                                                    <a href="{{ route('student.classes.task', ['class' => $class->id, 'task' => $task->id]) }}" class="btn btn-primary w-100">
                                                        View Tasks
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
