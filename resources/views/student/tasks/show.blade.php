@extends('layouts.student')

@section('title', $task->title)

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h4 mb-1">{{ $task->title }}</h1>
            <p class="text-muted mb-1">{{ $task->class->class_name }} - Group {{ $task->researchGroup?->Group_Name ?? 'N/A' }}</p>
        </div>
        <a href="{{ route('student.classes.show', $task->class) }}" class="btn btn-outline-secondary">← Back to Class</a>
    </div>

    {{-- Task Details --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Task Description</h5>
                </div>
                <div class="card-body">
                    {!! $task->description ? nl2br(e($task->description)) : '<p class="text-muted">No description provided.</p>' !!}
                    @if($task->file_path)
                        <div class="mt-3">
                            <strong>Attachment:</strong> <a href="{{ Storage::url($task->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Download File</a>
                        </div>
                    @endif
                    @if($task->due_date)
                        <div class="mt-3 p-2 bg-light rounded">
                            <strong>Due:</strong> <span class="badge {{ $task->due_date->isPast() ? 'bg-danger' : 'bg-warning' }}">{{ $task->due_date->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title">Teacher</h6>
                    <p>{{ $task->class->teacher?->firstname }} {{ $task->class->teacher?->Lastname }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Submission Form --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Submit Your Work</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('student.tasks.submit', $task) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Submission Text (optional)</label>
                    <textarea class="form-control" name="submission_text" rows="4" placeholder="Describe your work or answer here...">{{ old('submission_text', $submission?->submission_text ?? '') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">File Upload (optional, max 10MB)</label>
                    <input class="form-control" type="file" name="submission_file">
                </div>
                <button type="submit" class="btn btn-primary">Submit / Update Submission</button>
            </form>
        </div>
    </div>

    {{-- Submission History --}}
    @if($submission)
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Your Submission</h5>
            </div>
            <div class="card-body">
                <p><strong>Submitted:</strong> {{ $submission->submitted_at->format('M d, Y H:i') }}</p>
                @if($submission->submission_text)
                    <h6 class="mb-2">Text Submission</h6>
                    <p>{!! nl2br(e($submission->submission_text)) !!}</p>
                @endif
                @if($submission->file_path)
                    <h6 class="mb-2">File</h6>
                    <a href="{{ Storage::url($submission->file_path) }}" class="btn btn-outline-secondary btn-sm" target="_blank">View File</a>
                @endif
                @if($submission->comments->isNotEmpty())
                    <h6 class="mt-4 mb-3">Teacher Comments</h6>
                    @foreach($submission->comments->latest() as $comment)
                        <div class="border-bottom pb-2 mb-2">
                            <small class="text-muted">{{ $comment->teacher?->firstname }} {{ $comment->teacher?->Lastname }} - {{ $comment->created_at->format('M d') }}</small>
                            <p>{!! nl2br(e($comment->comment)) !!}</p>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No comments yet.</p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

