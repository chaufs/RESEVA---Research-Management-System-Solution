<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Class Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('Posts.Components.admin-styles')
</head>
<body>
    @include('Posts.Components.teacher-nav')

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                    @if($selectedGroup)
                        <a href="{{ route('teacher.classes.show', ['class' => $class->id]) }}" class="btn btn-outline-secondary">Back to group list</a>
                    @else
                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-secondary">Back to Class List</a>
                    @endif
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h1 class="mb-1">Manage Groups</h1>
                        <p class="text-muted mb-0">{{ $class->class_name }} · {{ $class->program?->program_name ?? 'Program not set' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h2 class="h5 mb-3">Class overview</h2>
                    <p class="mb-2"><strong>Class:</strong> {{ $class->class_name }}</p>
                    <p class="mb-2"><strong>Subject:</strong> {{ $class->subject ?? 'Not assigned' }}</p>
                    <p class="mb-2"><strong>Teacher:</strong> {{ trim(($class->teacher?->firstname ?? '') . ' ' . ($class->teacher?->Lastname ?? '')) ?: 'Not assigned' }}</p>
                    <p class="mb-2"><strong>Year Level:</strong> {{ $class->year_level ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Students:</strong> {{ $class->students->count() }}</p>

                    <div class="mt-4">
                        <h3 class="h6 mb-2">Assigned groups</h3>
                        @if($assignedGroups->isEmpty())
                            <p class="text-muted mb-0">No groups currently assigned.</p>
                        @else
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($assignedGroups as $group)
                                    <span class="badge bg-secondary">{{ $group->Group_Name ?? 'Group ' . $group->Group_ID }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-outline-primary mt-4" data-bs-toggle="modal" data-bs-target="#classListModal">View class list</button>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card p-4 mb-4">
                    @if($selectedGroup)
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h2 class="h5 mb-2">{{ $selectedGroup->Group_Name }}</h2>
                                <p class="text-muted mb-0">Members: {{ $selectedGroup->students->count() }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTaskModal">Assign task</button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#assignStudentsModal">Assign students</button>
                            </div>
                        </div>

                        <!-- Task Cards Section -->
                        @php
                            $groupTasks = $class->tasks->where('research_group_id', $selectedGroup->Group_ID);
                        @endphp
                        @if($groupTasks->isNotEmpty())
                            <div class="mb-4">
                                <h3 class="h6 mb-3">Group Tasks</h3>
                                <div class="accordion" id="tasksAccordion">
                                    @foreach($groupTasks as $index => $task)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $task->id }}">
                                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $task->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $task->id }}">
                                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                        <div>
                                                            <strong>{{ $task->title }}</strong>
                                                            <span class="badge bg-primary ms-2">Task</span>
                                                            @if($task->due_date)
                                                                <span class="badge {{ $task->due_date->isPast() ? 'bg-danger' : 'bg-warning' }} ms-1">
                                                                    Due {{ $task->due_date->format('M d') }}
                                                                </span>
                                                            @endif
                                                            <span class="badge bg-info text-dark ms-1">Max {{ $task->max_points ?? 100 }} pts</span>
                                                        </div>
                                                        <small class="text-muted">{{ $task->submissions->count() }} / {{ $task->max_submissions }} submissions</small>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $task->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $task->id }}" data-bs-parent="#tasksAccordion">
                                                <div class="accordion-body">
                                                    <p class="mb-3">{{ $task->description ?? 'No description' }}</p>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-outline-primary me-2 task-modal-trigger" data-bs-toggle="modal" data-bs-target="#taskViewModal" 
                                                                    data-task-id="{{ $task->id }}"
                                                                    data-task-title="{{ $task->title }}"
                                                                    data-task-description="{{ $task->description ?? 'No description' }}"
                                                                    data-task-due-date="{{ $task->due_date ? $task->due_date->format('Y-m-d H:i') : 'No due date' }}"
                                                                    data-task-file="{{ $task->file_path ? 'Attached' : 'None' }}"
                                                                    data-task-max-submissions="{{ $task->max_submissions }}"
                                                                    data-task-max-points="{{ $task->max_points ?? 100 }}"
                                                                    data-task-allow-late="{{ $task->allow_late_submission ? 'Yes' : 'No' }}"
                                                                    data-task-submissions-count="{{ $task->submissions->count() }}">
                                                                Edit Task
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <h6 class="mb-3">Student Submissions</h6>
                                                    @if($task->submissions->isNotEmpty())
                                                        <div class="row g-3">
                                                            @foreach($task->submissions as $submission)
                                                                @php
                                                                    $student = $submission->student;
                                                                @endphp
                                                                <div class="col-12">
                                                                    <div class="card border-light student-card"
                                                                         data-student-name="{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}"
                                                                         data-student-program="{{ $student->program?->program_name ?? 'Program not set' }}"
                                                                         data-student-status="{{ $submission->is_late ? 'Late' : 'On Time' }}"
                                                                         data-student-file="{{ $submission->file_path ? basename($submission->file_path) : '—' }}"
                                                                         data-student-file-url="{{ $submission->file_path ? Storage::url($submission->file_path) : '' }}"
                                                                         data-student-file-size="{{ $submission->file_path ? app('App\Helpers\FileHelper')->getFileSize($submission->file_path) : '' }}"
                                                                         data-student-max-points="{{ $submission->task->max_points ?? 100 }}"
                                                                         data-student-comments="{{ $submission->comments->pluck('comment')->join('<br>') ?: '—' }}"
                                                                         data-submission-id="{{ $submission->id }}"
                                                                         data-student-text="{{ $submission->submission_text ?: '—' }}">
                                                                        <div class="card-body">
                                                                            <div class="row g-3 align-items-center">
                                                                                <div class="col-12 col-md-4">
                                                                                    <h6 class="mb-1">{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</h6>
                                                                                    <small class="text-muted">{{ $student->program?->program_name ?? 'Program not set' }}</small>
                                                                                </div>
                                                                                <div class="col-12 col-md-5">
                                                                                    <div class="mb-3">
                                                                                        <small class="d-block mb-1">
                                                                                            <strong>Status:</strong>
                                                                                            <span class="badge {{ $submission->is_late ? 'bg-warning' : 'bg-success' }}">
                                                                                                {{ $submission->is_late ? 'Late' : 'On Time' }}
                                                                                            </span>
                                                                                        </small>
                                                                                        <small class="d-block">
                                                                                            <strong>Submitted:</strong> {{ $submission->submitted_at->format('M d, H:i') }}
                                                                                        </small>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-md-3 text-md-end">
                                                                                    <button type="button" class="btn btn-sm btn-primary submission-details-btn" data-bs-toggle="modal" data-bs-target="#studentSubmissionModal">
                                                                                        View details
                                                                                    </button>
                                                                                    @if($submission->comments->count() > 0)
                                                                                        <div class="mt-2 small text-muted">
                                                                                            {{ $submission->comments->count() }} comment{{ $submission->comments->count() > 1 ? 's' : '' }}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="alert alert-secondary">No submissions yet.</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                            @if($errors->hasAny(['group_id', 'student_ids', 'student_ids.*']))
                                                <div class="alert alert-danger">
                                                    Please choose at least one student to add to this group.
                                                </div>
                                            @endif
                                            <form action="{{ route('teacher.classes.group-students', $class) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="group_id" value="{{ $selectedGroup->Group_ID }}">
                                                <div class="row row-cols-1 row-cols-md-2 g-3">
                                                   
                                                </div>
                                                    
                                            </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                        <div class="mb-4">
                            

                            @if(session('assigned_task'))
                                @php $assignedTask = session('assigned_task'); @endphp
                                <div class="card border-primary mb-4">
                                    <div class="card-body">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                            <div>
                                                <h3 class="h6 mb-2">Last assigned task</h3>
                                                <h5 class="mb-1">{{ $assignedTask['title'] }}</h5>
                                                <p class="mb-1 text-muted">{{ $assignedTask['description'] ?: 'No description provided.' }}</p>
                                                <p class="mb-0"><strong>Due date:</strong> {{ $assignedTask['due_date'] ? \Illuminate\Support\Carbon::parse($assignedTask['due_date'])->format('M d, Y') : 'None' }}</p>
                                            </div>
                                            @if($assignedTask['file'])
                                                <div class="text-end">
                                                    <span class="badge bg-secondary">Instruction file uploaded</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                      
                    @else
                        <h2 class="h5 mb-3">Groups</h2>
                        @if($groups->isEmpty())
                            <div class="alert alert-info mb-0">No groups yet. Click Create group to add one.</div>
                        @else
                            <div class="row g-3">
                                @foreach($groups as $group)
                                    <div class="col-12 col-sm-6 col-xl-4">
                                        <a href="{{ route('teacher.classes.show', ['class' => $class, 'selected_group' => $group->Group_ID]) }}" class="text-decoration-none">
                                            <div class="card h-100 {{ $selectedGroup && $selectedGroup->Group_ID === $group->Group_ID ? 'border-primary shadow-sm' : 'border-secondary' }}">
                                                <div class="card-body">
                                                    <h3 class="h6 mb-2">{{ $group->Group_Name }}</h3>
                                                    <p class="mb-1 small text-muted">{{ $group->students->count() }} student{{ $group->students->count() === 1 ? '' : 's' }}</p>
                                                    <span class="small text-primary">Click to view the group</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @if($selectedGroup)
            <!-- Assign task modal -->
            <div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignTaskModalLabel">Assign task to {{ $selectedGroup->Group_Name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('teacher.classes.groups.assign-task', ['class' => $class->id, 'group' => $selectedGroup->Group_ID]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <label for="task_title" class="form-label">Task title</label>
                                            <input
                                                id="task_title"
                                                name="task_title"
                                                type="text"
                                                class="form-control @error('task_title') is-invalid @enderror"
                                                value="{{ old('task_title') }}"
                                                placeholder="Enter task name"
                                            >
                                            @error('task_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <label for="due_date" class="form-label">Due date</label>
                                            <input
                                                id="due_date"
                                                name="due_date"
                                                type="date"
                                                class="form-control @error('due_date') is-invalid @enderror"
                                                value="{{ old('due_date') }}"
                                            >
                                            @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <label for="max_submissions" class="form-label">Max submissions</label>
                                            <input
                                                id="max_submissions"
                                                name="max_submissions"
                                                type="number"
                                                class="form-control @error('max_submissions') is-invalid @enderror"
                                                value="{{ old('max_submissions', 1) }}"
                                                min="1"
                                            >
                                            <div class="form-text">Maximum number of times a student can submit this task.</div>
                                            @error('max_submissions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <label for="max_points" class="form-label">Max points</label>
                                            <input
                                                id="max_points"
                                                name="max_points"
                                                type="number"
                                                class="form-control @error('max_points') is-invalid @enderror"
                                                value="{{ old('max_points', 100) }}"
                                                min="1"
                                            >
                                            <div class="form-text">Maximum score a student can earn for this task.</div>
                                            @error('max_points')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <div class="form-check mt-4">
                                                <input
                                                    id="allow_late_submission"
                                                    name="allow_late_submission"
                                                    type="checkbox"
                                                    class="form-check-input @error('allow_late_submission') is-invalid @enderror"
                                                    value="1"
                                                    {{ old('allow_late_submission') ? 'checked' : '' }}
                                                >
                                                <label for="allow_late_submission" class="form-check-label">
                                                    Allow late submissions
                                                </label>
                                                <div class="form-text">If enabled, students can submit after the due date. Late submissions will be marked.</div>
                                            </div>
                                            @error('allow_late_submission')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="task_description" class="form-label">Task description</label>
                                            <textarea
                                                id="task_description"
                                                name="task_description"
                                                rows="4"
                                                class="form-control @error('task_description') is-invalid @enderror"
                                                placeholder="Describe the assignment"
                                            >{{ old('task_description') }}</textarea>
                                            @error('task_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="task_file" class="form-label">Upload instruction file (optional)</label>
                                            <input
                                                id="task_file"
                                                name="task_file"
                                                type="file"
                                                class="form-control @error('task_file') is-invalid @enderror"
                                            >
                                            @error('task_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create group task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if($selectedGroup)
            <!-- Assign students modal -->
            <div class="modal fade" id="assignStudentsModal" tabindex="-1" aria-labelledby="assignStudentsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignStudentsModalLabel">Add students to {{ $selectedGroup->Group_Name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('teacher.classes.group-students', $class) }}" method="POST">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $selectedGroup->Group_ID }}">
                            <div class="modal-body">
                                @php $ungroupedStudents = $class->students->whereNull('Group_ID'); @endphp
                                @if($ungroupedStudents->isEmpty())
                                    <div class="alert alert-info mb-0">No ungrouped students are available for this class.</div>
                                @else
                                    <div class="list-group">
                                        @foreach($ungroupedStudents as $student)
                                            <label class="list-group-item list-group-item-action d-flex align-items-start gap-3">
                                                <input type="checkbox" class="form-check-input mt-1" name="student_ids[]" value="{{ $student->student_id }}">
                                                <div>
                                                    <div class="fw-semibold">{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</div>
                                                    <div class="small text-muted">{{ $student->program?->program_name ?? 'Program not set' }}</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                                @if($errors->hasAny(['group_id', 'student_ids', 'student_ids.*']))
                                    <div class="alert alert-danger mt-3">Please choose at least one student to add to this group.</div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add selected students</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Task View Modal -->
        <div class="modal fade" id="taskViewModal" tabindex="-1" aria-labelledby="taskViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskViewModalLabel">Task Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 id="taskTitle" class="mb-3"></h5>
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p id="taskDescription" class="text-muted mt-1 mb-0"></p>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <strong>Due Date:</strong>
                                <p id="taskDueDate" class="text-muted mb-0"></p>
                            </div>
                            <div class="col-6">
                                <strong>File:</strong>
                                <p id="taskFile" class="text-muted mb-0"></p>
                            </div>
                            <div class="col-6">
                                <strong>Max Submissions:</strong>
                                <p id="taskMaxSubmissions" class="text-muted mb-0"></p>
                            </div>
                            <div class="col-6">
                                <strong>Max Points:</strong>
                                <p id="taskMaxPoints" class="text-muted mb-0"></p>
                            </div>
                            <div class="col-6">
                                <strong>Allow Late Submission:</strong>
                                <p id="taskAllowLate" class="text-muted mb-0"></p>
                            </div>
                            <div class="col-6">
                                <strong>Current Submissions:</strong>
                                <p id="taskSubmissionsCount" class="text-muted mb-0"></p>
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3">Edit Task</h6>
                        <form id="taskEditForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="edit_task_title" class="form-label">Task Title</label>
                                    <input type="text" name="task_title" id="edit_task_title" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label for="edit_task_description" class="form-label">Description</label>
                                    <textarea name="task_description" id="edit_task_description" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="col-6">
                                    <label for="edit_due_date" class="form-label">Due Date</label>
                                    <input type="datetime-local" name="due_date" id="edit_due_date" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label for="edit_max_submissions" class="form-label">Max Submissions</label>
                                    <input type="number" name="max_submissions" id="edit_max_submissions" class="form-control" min="1" max="100" required>
                                </div>
                                <div class="col-6">
                                    <label for="edit_max_points" class="form-label">Max Points</label>
                                    <input type="number" name="max_points" id="edit_max_points" class="form-control" min="1" max="1000" required>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="allow_late_submission" id="edit_allow_late_submission" class="form-check-input" value="1">
                                        <label for="edit_allow_late_submission" class="form-check-label">Allow late submission</label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#taskDeleteModal">Delete</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Delete Confirmation Modal -->
        <div class="modal fade" id="taskDeleteModal" tabindex="-1" aria-labelledby="taskDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskDeleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this task? This action can be undone later.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="taskDeleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Task</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student submission modal -->
        <div class="modal fade" id="studentSubmissionModal" tabindex="-1" aria-labelledby="studentSubmissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="studentSubmissionModalLabel">Student submission details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <h5 id="submissionStudentName" class="mb-2"></h5>
                        <p id="submissionStudentProgram" class="text-muted small mb-3"></p>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            <span id="submissionStatus" class="text-muted">No submission yet</span>
                        </div>
                        <div class="mb-2">
                            <strong>Submitted file:</strong>
                            <span id="submissionFile" class="text-muted">—</span>
                        </div>
                        <div class="mb-2" id="submissionFileActions"></div>
                        <div class="mb-2">
                            <strong>File size:</strong>
                            <span id="submissionFileSize" class="text-muted">—</span>
                        </div>
                        <div class="mb-2">
                            <strong>Submission Text:</strong>
                            <div id="submissionText" class="mt-1 text-muted">—</div>
                        </div>
                        <div class="mb-2">
                            <strong>Grade:</strong>
                            <span id="submissionGrade" class="text-muted">—</span>
                        </div>
                        <div class="mb-3">
                            <strong>Comments:</strong>
                            <div id="submissionCommentsList" class="mt-2"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Grade Submission</h6>
                                <form id="gradeForm" method="POST" action="">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="gradeInput" class="form-label">Grade</label>
                                        <input type="number" name="grade" id="gradeInput" class="form-control" min="0" max="100" step="0.01" placeholder="Enter grade">
                                    </div>
                                    <button type="submit" class="btn btn-success">Assign Grade</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Add Comment</h6>
                                <form id="commentForm" method="POST" action="">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="comment" class="form-control" rows="3" placeholder="Write your feedback or comment here..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class list modal -->
        <div class="modal fade" id="classListModal" tabindex="-1" aria-labelledby="classListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="classListModalLabel">Class student list</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if($class->students->isEmpty())
                            <p class="text-muted mb-0">No students assigned to this class yet.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($class->students as $student)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</strong>
                                            <div class="text-muted small">{{ $student->program?->program_name ?? 'Program not set' }}</div>
                                        </div>
                                        <span class="badge bg-secondary">{{ $student->researchGroup?->Group_Name ?? 'Ungrouped' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.submission-details-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var card = this.closest('.student-card');
                if (!card) {
                    return;
                }

                var name = card.dataset.studentName || 'Student';
                var program = card.dataset.studentProgram || '';
                var status = card.dataset.studentStatus || 'No submission yet';
                var file = card.dataset.studentFile || '—';
                var fileUrl = card.dataset.studentFileUrl || '';
                var fileSize = card.dataset.studentFileSize || '—';
                var grade = card.dataset.studentGrade || '—';
                var comments = card.dataset.studentComments || '—';
                var submissionId = card.dataset.submissionId || '';
                var text = card.dataset.studentText || '—';
                var maxPoints = card.dataset.studentMaxPoints || '100';

                document.getElementById('submissionStudentName').textContent = name;
                document.getElementById('submissionStudentProgram').textContent = program;
                document.getElementById('submissionStatus').textContent = status;
                document.getElementById('submissionFile').textContent = file;
                document.getElementById('submissionFileSize').textContent = fileSize || '—';
                document.getElementById('submissionGrade').textContent = grade + ' / ' + maxPoints;
                document.getElementById('submissionText').innerHTML = text;

                var fileActions = document.getElementById('submissionFileActions');
                if (fileUrl) {
                    fileActions.innerHTML = '<a href="' + fileUrl + '" target="_blank" class="btn btn-sm btn-outline-info me-2">View</a>' +
                                            '<a href="' + fileUrl + '" download class="btn btn-sm btn-outline-primary">Download</a>';
                } else {
                    fileActions.innerHTML = '';
                }
                
                // Update comments display
                var commentsList = document.getElementById('submissionCommentsList');
                if (comments && comments !== '—') {
                    commentsList.innerHTML = '<div class="p-3 bg-light rounded">' + comments + '</div>';
                } else {
                    commentsList.innerHTML = '<p class="text-muted mb-0">No comments yet.</p>';
                }
                
                // Update comment form action
                var commentForm = document.getElementById('commentForm');
                var gradeForm = document.getElementById('gradeForm');
                if (submissionId) {
                    commentForm.action = '/teacher/submissions/' + submissionId + '/comment';
                    commentForm.style.display = 'block';
                    
                    // Update grade form action
                    gradeForm.action = '/teacher/submissions/' + submissionId + '/grade';
                    gradeForm.style.display = 'block';
                    
                    // Set current grade value
                    document.getElementById('gradeInput').value = grade !== '—' ? grade : '';
                    document.getElementById('gradeInput').max = maxPoints;
                    document.getElementById('gradeInput').placeholder = '0 - ' + maxPoints;
                } else {
                    commentForm.style.display = 'none';
                    gradeForm.style.display = 'none';
                }
            });
        });

        // Task card modal functionality
        document.querySelectorAll('.task-modal-trigger').forEach(function (card) {
            card.addEventListener('click', function () {
                var taskId = this.dataset.taskId || '';
                var title = this.dataset.taskTitle || '';
                var description = this.dataset.taskDescription || '';
                var dueDate = this.dataset.taskDueDate || '';
                var file = this.dataset.taskFile || '';
                var maxSubmissions = this.dataset.taskMaxSubmissions || '';
                var maxPoints = this.dataset.taskMaxPoints || '100';
                var allowLate = this.dataset.taskAllowLate || '';
                var submissionsCount = this.dataset.taskSubmissionsCount || '';

                document.getElementById('taskTitle').textContent = title;
                document.getElementById('taskDescription').textContent = description;
                document.getElementById('taskDueDate').textContent = dueDate;
                document.getElementById('taskFile').textContent = file;
                document.getElementById('taskMaxSubmissions').textContent = maxSubmissions;
                document.getElementById('taskMaxPoints').textContent = maxPoints;
                document.getElementById('taskAllowLate').textContent = allowLate;
                document.getElementById('taskSubmissionsCount').textContent = submissionsCount;

                // Populate edit form
                document.getElementById('edit_task_title').value = title;
                document.getElementById('edit_task_description').value = description === 'No description' ? '' : description;
                document.getElementById('edit_due_date').value = dueDate !== 'No due date' ? dueDate.replace(' ', 'T').substring(0, 16) : '';
                document.getElementById('edit_max_submissions').value = maxSubmissions;
                document.getElementById('edit_max_points').value = maxPoints;
                document.getElementById('edit_allow_late_submission').checked = allowLate === 'Yes';

                // Set form actions
                var editForm = document.getElementById('taskEditForm');
                editForm.action = '/teacher/tasks/' + taskId;

                var deleteForm = document.getElementById('taskDeleteForm');
                deleteForm.action = '/teacher/tasks/' + taskId;
            });
        });
    </script>

    @if($selectedGroup && $errors->hasAny(['task_title', 'task_description', 'due_date', 'task_file', 'max_points']))
        <script>
            var assignTaskModal = new bootstrap.Modal(document.getElementById('assignTaskModal'));
            assignTaskModal.show();
        </script>
    @endif

    @if($selectedGroup && $errors->hasAny(['group_id', 'student_ids', 'student_ids.*']))
        <script>
            var assignStudentsModal = new bootstrap.Modal(document.getElementById('assignStudentsModal'));
            assignStudentsModal.show();
        </script>
    @endif
</body>
</html>
