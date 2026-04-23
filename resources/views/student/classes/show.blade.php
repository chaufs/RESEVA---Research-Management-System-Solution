@extends('layouts.student')

@section('title', $class->class_name)

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 mb-1">{{ $class->class_name }}</h1>
            <p class="text-muted">{{ $class->subject }} - Year {{ $class->year_level }}</p>
        </div>
        <a href="{{ route('student.classes') }}" class="btn btn-outline-secondary">← Back to Classes</a>
    </div>

    {{-- Class Info --}}
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Class Details</h5>
                    <p class="mb-1"><strong>Teacher:</strong> {{ $class->teacher?->firstname }} {{ $class->teacher?->Lastname ?? 'Not assigned' }}</p>
                    <p class="mb-1"><strong>Program:</strong> {{ $class->program?->program_name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-{{ strtolower($class->status) === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($class->status) }}</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tasks by Group --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Assigned Tasks</h5>
        </div>
        <div class="card-body">
            @if($tasksByGroup->isEmpty())
                <div class="alert alert-info">No tasks assigned to this class yet.</div>
            @else
                @foreach($tasksByGroup as $groupId => $tasks)
                    @php $group = $class->assignedGroups->firstWhere('Group_ID', $groupId) @endphp
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="mb-2">Group {{ $group?->Group_Name ?? 'Ungrouped' }}</h6>
                        <div class="row g-3">
                            @foreach($tasks as $task)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <h6 class="card-title text-primary mb-2">{{ $task->title }}</h6>
                                            @if($task->description)
                                                <p class="small mb-2">{{ Str::limit($task->description, 80) }}</p>
                                            @endif
                                            @if($task->due_date)
                                                <span class="badge {{ $task->due_date->isPast() ? 'bg-danger' : 'bg-warning' }}">{{ $task->due_date->diffForHumans() }}</span>
                                            @endif
                                            <div class="mt-3">
                                                <a href="{{ route('student.classes.task', [$class, $task]) }}" class="btn btn-outline-primary btn-sm w-100">View & Submit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

