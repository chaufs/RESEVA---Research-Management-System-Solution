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
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">Back to My Classes</a>
                    <form action="{{ route('teacher.classes.create-group', $class) }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <button type="submit" class="btn btn-success">Create group</button>
                    </form>
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
                            <a href="{{ route('teacher.classes.show', ['class' => $class]) }}" class="btn btn-outline-secondary">Back to group list</a>
                        </div>

                        <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
                            @foreach($selectedGroup->students as $student)
                                <div class="col">
                                    <button type="button" class="card h-100 text-start text-decoration-none border-secondary student-card p-0" data-bs-toggle="modal" data-bs-target="#studentSubmissionModal"
                                        data-student-name="{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}"
                                        data-student-program="{{ $student->program?->program_name ?? 'Program not set' }}"
                                        data-student-status="No submission yet"
                                        data-student-file="—"
                                        data-student-grade="—"
                                        data-student-comments="—"
                                    >
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h5 class="card-title mb-1">{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</h5>
                                                    <p class="small text-muted mb-1">{{ $student->program?->program_name ?? 'Program not set' }}</p>
                                                </div>
                                                <span class="badge bg-secondary">{{ $student->researchGroup?->Group_Name ?? 'Ungrouped' }}</span>
                                            </div>

                                            <div class="mt-3">
                                                <p class="mb-1 small"><strong>Status:</strong> <span class="text-muted">No submission yet</span></p>
                                                <p class="mb-1 small"><strong>File:</strong> <span class="text-muted">—</span></p>
                                                <p class="mb-1 small"><strong>Grade:</strong> <span class="text-muted">—</span></p>
                                                <p class="mb-0 small"><strong>Comments:</strong> <span class="text-muted">—</span></p>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h3 class="h6 mb-0">Assign task to {{ $selectedGroup->Group_Name }}</h3>
                                    <p class="text-muted small mb-0">Press the button to open the task assignment popup.</p>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignTaskModal">
                                    Assign task
                                </button>
                            </div>

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

                        <div class="card p-4">
                            <h3 class="h6 mb-3">Group submissions</h3>
                            <p class="text-muted small mb-0">Click a student card above to view submission details inside the card.</p>
                        </div>
                    @else
                        <h2 class="h5 mb-3">Groups</h2>
                        @if($groups->isEmpty())
                            <p class="text-muted mb-0">No groups yet. Click Create group to add one.</p>
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
        </div>

        <!-- Assign task modal -->
        <div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
<h5 class="modal-title" id="assignTaskModalLabel">Assign task to {{ $selectedGroup->Group_Name ?? 'Selected Group' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
<form action="{{ route('teacher.classes.groups.assign-task', ['class' => $class, 'group' => $selectedGroup]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="task_title" class="form-label">Task title</label>
                                        <input id="task_title" name="task_title" type="text" class="form-control @error('task_title') is-invalid @enderror" value="{{ old('task_title') }}" placeholder="Enter task name">
                                        @error('task_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">Due date</label>
                                        <input id="due_date" name="due_date" type="date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="task_description" class="form-label">Task description</label>
                                        <textarea id="task_description" name="task_description" rows="4" class="form-control @error('task_description') is-invalid @enderror" placeholder="Describe the assignment">{{ old('task_description') }}</textarea>
                                        @error('task_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="task_file" class="form-label">Upload instruction file (optional)</label>
                                        <input id="task_file" name="task_file" type="file" class="form-control @error('task_file') is-invalid @enderror">
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

        <!-- Student submission modal -->
        <div class="modal fade" id="studentSubmissionModal" tabindex="-1" aria-labelledby="studentSubmissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
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
                        <div class="mb-2">
                            <strong>Grade:</strong>
                            <span id="submissionGrade" class="text-muted">—</span>
                        </div>
                        <div class="mb-0">
                            <strong>Comments:</strong>
                            <p id="submissionComments" class="text-muted mb-0">—</p>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.student-card').forEach(function(card) {
            card.addEventListener('click', function () {
                var name = this.dataset.studentName || 'Student';
                var program = this.dataset.studentProgram || '';
                var status = this.dataset.studentStatus || 'No submission yet';
                var file = this.dataset.studentFile || '—';
                var grade = this.dataset.studentGrade || '—';
                var comments = this.dataset.studentComments || '—';

                document.getElementById('submissionStudentName').textContent = name;
                document.getElementById('submissionStudentProgram').textContent = program;
                document.getElementById('submissionStatus').textContent = status;
                document.getElementById('submissionFile').textContent = file;
                document.getElementById('submissionGrade').textContent = grade;
                document.getElementById('submissionComments').textContent = comments;
            });
        });
    </script>
    @if($errors->any())
        <script>
            var assignTaskModal = new bootstrap.Modal(document.getElementById('assignTaskModal'));
            assignTaskModal.show();
        </script>
    @endif
</body>
</html>
