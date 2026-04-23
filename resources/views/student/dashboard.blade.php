@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h4 mb-1">Welcome, {{ $student->SFname }} {{ $student->SLname }}</h1>
            <p class="text-muted mb-0">Review your recent activities and enrolled classes.</p>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="row gy-3 mb-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Profile</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</p>
                    <p class="mb-1"><strong>Program:</strong> {{ $student->program?->program_name ?? 'Not assigned' }}</p>
                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($student->status) }}</span></p>
                    <p class="mb-0"><strong>Group:</strong> {{ $student->researchGroup?->Group_Name ?? 'Ungrouped' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="studentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab">Recent Activities</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab">My Classes</button>
        </li>
    </ul>

    <div class="tab-content" id="studentTabContent">
        {{-- Recent Activities Tab --}}
        <div class="tab-pane fade show active" id="recent" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Tasks</h5>
                    @if($recentTasks->isEmpty())
                        <div class="alert alert-info">No recent tasks. Check your classes for assignments.</div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentTasks as $task)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><a href="{{ route('student.classes.show', $task->class) }}" class="text-decoration-none">{{ $task->title }}</a></h6>
                                            <p class="small text-muted mb-1">{{ $task->class->class_name }} - {{ $task->class->teacher?->firstname }} {{ $task->class->teacher?->Lastname }}</p>
                                            @if($task->description)
                                                <p class="small mb-1">{{ Str::limit($task->description, 100) }}</p>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            @if($task->due_date)
                                                <span class="badge {{ $task->due_date->isPast() ? 'bg-danger' : 'bg-warning' }}">{{ $task->due_date->format('M d') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- My Classes Tab --}}
        <div class="tab-pane fade" id="classes" role="tabpanel">
            @if($classes->isEmpty())
                <div class="alert alert-info">You are not enrolled in any classes yet.</div>
            @else
                <div class="row g-4">
                    @foreach($classes as $class)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">{{ $class->class_name }}</h6>
                                    <p class="small text-muted mb-2">{{ $class->subject ?? 'No subject' }}</p>
                                    <p class="small mb-3">Teacher: {{ $class->teacher?->firstname ?? '' }} {{ $class->teacher?->Lastname ?? 'Not assigned' }}</p>
                                    <span class="badge bg-secondary mb-2">Year {{ $class->year_level ?? 'N/A' }}</span>
                                    <div class="mt-3">
                                        <a href="{{ route('student.classes.show', $class) }}" class="btn btn-primary btn-sm">View Tasks</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

