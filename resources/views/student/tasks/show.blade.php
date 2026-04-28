@extends('layouts.student')

@section('title', $task->title)

@section('content')
<div class="container">
    <div class="student-hero p-4 p-lg-5 mb-4 position-relative">
        <div class="pe-lg-5">
            <span class="badge student-badge rounded-pill mb-3 px-3 py-2">Task Details</span>
            <h1 class="display-6 fw-bold mb-2">{{ $task->title }}</h1>
            <p class="text-muted mb-0 fs-5">
                {{ $task->class->class_name }} · Group {{ $task->researchGroup?->Group_Name ?? 'Ungrouped' }}
            </p>
        </div>

        <a href="{{ route('student.classes.show', $task->class) }}" class="btn btn-outline-secondary btn-sm position-absolute top-0 end-0 m-4">
            ← Back to Class
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card student-card">
                <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <h5 class="mb-0 student-section-title">Task Details</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Class</div>
                                <div class="fw-semibold">{{ $task->class->class_name }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Group</div>
                                <div class="fw-semibold">{{ $task->researchGroup?->Group_Name ?? 'Ungrouped' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Teacher</div>
                                <div class="fw-semibold">{{ trim(($task->class->teacher?->firstname ?? '') . ' ' . ($task->class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Due Date</div>
                                @if($task->due_date)
                                    <span class="badge {{ $task->due_date->isPast() ? 'text-bg-danger' : 'text-bg-warning' }}">
                                        {{ $task->due_date->format('M d, Y H:i') }}
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary">No due date</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Max Submissions</div>
                                <div class="fw-semibold">{{ $task->max_submissions ?? 1 }} time(s)</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Late Submission</div>
                                <div class="fw-semibold">
                                    @if($task->allow_late_submission)
                                        <span class="badge text-bg-success">Allowed</span>
                                    @else
                                        <span class="badge text-bg-secondary">Not Allowed</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="text-muted small mb-1">Submission Status</div>
                                @if($submission)
                                    <div class="student-empty-state mb-2">
                                        <div class="text-muted small mb-1">Submitted</div>
                                        <div class="fw-semibold">
                                            {{ $submission->submitted_at?->format('M d, Y H:i') ?? 'Not available' }}
                                            @if($submission->is_late)
                                                <span class="badge text-bg-warning ms-2">Late Submission</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="student-empty-state mb-2">
                                        <div class="text-muted small mb-1">Submission #</div>
                                        <div class="fw-semibold">{{ $submission->submission_count ?? 1 }}</div>
                                    </div>
                                    <div class="student-empty-state mb-2">
                                        <div class="text-muted small mb-1">File</div>
                                        <div class="fw-semibold">{{ $submission->file_path ? 'Attached' : 'No file attached' }}</div>
                                    </div>
                                    <div class="student-empty-state mb-2">
                                        <div class="text-muted small mb-1">Comments</div>
                                        <div class="fw-semibold">{{ $submission->comments->count() }}</div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#submissionModal">
                                            View My Submission & Feedback
                                        </button>
                                    </div>
                                @else
                                    <div class="student-empty-state text-center">
                                        <h6 class="mb-2">No submission yet</h6>
                                        <p class="text-muted mb-0">Submit your work using the form below.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- My Submission & Teacher Feedback Modal --}}
                    @if($submission)
                    <div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title student-section-title" id="submissionModalLabel">My Submission & Feedback</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <h6 class="student-section-title mb-3">My Submission</h6>
                                            <div class="student-empty-state mb-3">
                                                @if($submission->submission_text)
                                                    <div class="mb-3">
                                                        <div class="text-muted small mb-1">Submission Text</div>
                                                        <div class="fw-semibold">{{ $submission->submission_text }}</div>
                                                    </div>
                                                @endif
                                                @if($submission->file_path)
                                                    <div class="mb-3">
                                                        <div class="text-muted small mb-1">Attached File</div>
                                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                                            View Attached File
                                                        </a>
                                                    </div>
                                                @endif
                                                @if(!$submission->submission_text && !$submission->file_path)
                                                    <p class="text-muted mb-0">No content submitted.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <h6 class="student-section-title mb-3">Teacher Feedback</h6>
                                            @if($submission->comments && $submission->comments->count() > 0)
                                                @foreach($submission->comments as $comment)
                                                    <div class="student-empty-state mb-2">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div class="text-muted small mb-0">
                                                                <strong>{{ trim($comment->teacher?->firstname . ' ' . $comment->teacher?->Lastname) }}</strong>
                                                                &middot; {{ $comment->created_at->format('M d, Y H:i') }}
                                                            </div>
                                                            <span class="badge text-bg-success">Reviewed</span>
                                                        </div>
                                                        <div class="fw-semibold">{{ $comment->comment }}</div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="student-empty-state text-center">
                                                    <span class="badge text-bg-secondary">Pending Review</span>
                                                    <p class="text-muted mb-0 mt-2">Your submission has not been reviewed yet.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mt-4">
                        <h5 class="student-section-title mb-3">Description</h5>
                        <div class="student-empty-state">
                            {!! $task->description ? nl2br(e($task->description)) : '<p class="text-muted mb-0">No description provided.</p>' !!}
                        </div>
                        @if($task->file_path)
                            <div class="mt-3">
                                <a href="{{ Storage::url($task->file_path) }}" target="_blank" class="btn btn-outline-secondary">
                                    Download Instruction File
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card student-card mt-4">
                <div class="card-header py-3">
                    <h5 class="mb-0 student-section-title">Submit Your Work</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @php
                        $maxSubmissions = $task->max_submissions ?? 1;
                        $canSubmit = $submissionCount < $maxSubmissions;
                    @endphp

                    @if(!$canSubmit)
                        <div class="alert alert-warning">
                            <strong>Maximum submissions reached!</strong>
                            You have already submitted {{ $submissionCount }} time(s) for this task. No more submissions are allowed.
                        </div>
                    @endif

                    <form action="{{ route('student.tasks.submit', $task) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Submission Text (optional)</label>
                            <textarea class="form-control" name="submission_text" rows="5" placeholder="Describe your work or answer here..." {{ !$canSubmit ? 'disabled' : '' }}>{{ old('submission_text', $submission?->submission_text ?? '') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">File Upload (optional, max 10MB)</label>
                            <input class="form-control" type="file" name="submission_file" {{ !$canSubmit ? 'disabled' : '' }}>
                        </div>
                        <button type="submit" class="btn btn-primary" {{ !$canSubmit ? 'disabled' : '' }}>
                            {{ $canSubmit ? 'Submit / Update Submission' : 'Submission Limit Reached' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
