<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Class</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('Posts.Components.admin-styles')
</head>
<body>
    @include('Posts.Components.navba')

    <div class="container mt-4">
        <div class="page-header mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h1 class="mb-0">Class Assignment</h1>
                <p class="text-muted mb-0">Create classes and assign students or teachers when you are ready.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#createClassForm" aria-expanded="false" aria-controls="createClassForm">
                    Create New Class
                </button>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#assignClassForm" aria-expanded="false" aria-controls="assignClassForm">
                    Assign to Existing Class
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="collapse mb-4" id="createClassForm">
            <div class="card card-body">
                <h2 class="h5 mb-3">New Class Details</h2>
                <form action="{{ route('adminclass.storeClass') }}" method="POST">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label for="class_name" class="form-label">Class Code</label>
                            <input type="text" id="class_name" name="class_name" value="{{ old('class_name') }}" class="form-control @error('class_name') is-invalid @enderror" placeholder="e.g. CS101">
                            @error('class_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="program_id" class="form-label">Program</label>
                            <select id="program_id" name="program_id" class="form-select @error('program_id') is-invalid @enderror">
                                <option value="">Choose a program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->program_id }}" {{ old('program_id') == $program->program_id ? 'selected' : '' }}>{{ $program->program_name }}</option>
                                @endforeach
                            </select>
                            @error('program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror">
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="year_level" class="form-label">Year Level</label>
                            <input type="number" id="year_level" name="year_level" min="1" max="4" value="{{ old('year_level', 1) }}" class="form-control @error('year_level') is-invalid @enderror">
                            @error('year_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="teacher_id" class="form-label">Assign Teacher</label>
                            <select id="teacher_id" name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                                <option value="">Choose a teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->firstname }} {{ $teacher->Lastname }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="max_capacity" class="form-label">Max Capacity</label>
                            <input type="number" id="max_capacity" name="max_capacity" min="1" value="{{ old('max_capacity', 30) }}" class="form-control @error('max_capacity') is-invalid @enderror">
                            @error('max_capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="student_ids" class="form-label">Add Students to Class</label>
                            <select id="student_ids" name="student_ids[]" class="form-select @error('student_ids') is-invalid @enderror" multiple size="10">
                                @foreach($students as $student)
                                    <option value="{{ $student->student_id }}" {{ collect(old('student_ids'))->contains($student->student_id) ? 'selected' : '' }}>{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</option>
                                @endforeach
                            </select>
                            @error('student_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Create Class</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="collapse mb-4" id="assignClassForm">
            <div class="card card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Assign Students or Teacher to Existing Class</h2>
                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#assignClassForm" aria-expanded="true" aria-controls="assignClassForm">
                        Close
                    </button>
                </div>
                <form action="{{ route('adminclass.assign') }}" method="POST">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <label for="class_id" class="form-label">Select Class</label>
                            <select id="class_id" name="class_id" class="form-select @error('class_id') is-invalid @enderror">
                                <option value="">Choose a class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">
                                        {{ $class->class_name }}
                                        @if($class->program)
                                            ({{ $class->program->program_name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="teacher_id" class="form-label">Assign Teacher</label>
                            <select id="teacher_id" name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                                <option value="">Choose a teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->firstname }} {{ $teacher->Lastname }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="student_ids" class="form-label">Assign Students</label>
                            <select id="student_ids" name="student_ids[]" class="form-select @error('student_ids') is-invalid @enderror" multiple size="10">
                                @foreach($students as $student)
                                    <option value="{{ $student->student_id }}">{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</option>
                                @endforeach
                            </select>
                            @error('student_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save Assignment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($classes->isEmpty())
            <div class="alert alert-warning">
                No classes found. Please create classes before assigning students or teachers.
            </div>
        @else
            <hr class="my-4">

            <h2 class="h5 mb-3">Existing class assignments</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @foreach($classes as $class)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0">{{ $class->class_name }}</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-sm badge bg-{{ $class->status === 'active' ? 'success' : ($class->status === 'inactive' ? 'secondary' : 'dark') }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ ucfirst($class->status) }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('adminclass.toggleStatus', ['class' => $class->id, 'status' => 'active']) }}">Active</a></li>
                                            <li><a class="dropdown-item" href="{{ route('adminclass.toggleStatus', ['class' => $class->id, 'status' => 'inactive']) }}">Inactive</a></li>
                                            <li><a class="dropdown-item" href="{{ route('adminclass.toggleStatus', ['class' => $class->id, 'status' => 'archived']) }}">Archived</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="card-text mb-2"><strong>Subject:</strong> {{ $class->subject }}</p>
                                <p class="card-text mb-2"><strong>Teacher:</strong> {{ $class->teacher?->firstname }} {{ $class->teacher?->Lastname ?? 'Unassigned' }}</p>
                                <p class="card-text mb-3"><strong>Students:</strong> {{ $class->students->count() }} assigned</p>
                                <button class="btn btn-outline-primary mt-auto" type="button" data-bs-toggle="modal" data-bs-target="#classDetailsModal-{{ $class->id }}">
                                    View details
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="classDetailsModal-{{ $class->id }}" tabindex="-1" aria-labelledby="classDetailsModalLabel-{{ $class->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="classDetailsModalLabel-{{ $class->id }}">Class Details: {{ $class->class_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4">Class Code</dt>
                                        <dd class="col-sm-8">{{ $class->class_name }}</dd>

                                        <dt class="col-sm-4">Subject</dt>
                                        <dd class="col-sm-8">{{ $class->subject }}</dd>

                                        <dt class="col-sm-4">Teacher</dt>
                                        <dd class="col-sm-8">{{ $class->teacher?->firstname }} {{ $class->teacher?->Lastname ?? 'Unassigned' }}</dd>

                                        <dt class="col-sm-4">Program</dt>
                                        <dd class="col-sm-8">{{ $class->program?->program_name ?? 'Unassigned' }}</dd>

                                        <dt class="col-sm-4">Year Level</dt>
                                        <dd class="col-sm-8">{{ $class->year_level }}</dd>

                                        <dt class="col-sm-4">Status</dt>
                                        <dd class="col-sm-8">{{ ucfirst($class->status) }}</dd>
                                    </dl>

                                    <hr>

                                    <h6>Student list</h6>
                                    @if($class->students->isEmpty())
                                        <p class="text-muted mb-0">No students assigned.</p>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($class->students as $student)
                                                <li class="list-group-item py-2">
                                                    {{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}
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
                @endforeach
            </div>
            <div class="mt-4">
                {{ $classes->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>


