<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin User Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('Posts.Components.admin-styles')
</head>
<body>
    @include('Posts.Components.navba')

    <div class="container mt-4">
        <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div>
                <h1 class="mb-1">User Management</h1>
                <p class="text-muted mb-0">Create and review teachers and students from one place.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('user-management.create-teacher') }}" class="btn btn-primary">Create Teacher</a>
                <a href="{{ route('user-management.create-student') }}" class="btn btn-outline-primary">Create Student</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab" aria-controls="teachers" aria-selected="true">Teachers</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">Students</button>
            </li>
        </ul>

        <div class="tab-content" id="userTabsContent">
            <div class="tab-pane fade show active" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Teachers</h5>
                    </div>
                    <div class="card-body">
                        @if($teachers->isEmpty())
                            <div class="alert alert-warning mb-0">
                                No teachers found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teachers as $teacher)
                                            <tr>
                                                <td>{{ $teacher->firstname }} {{ $teacher->Middlename }} {{ $teacher->Lastname }}</td>
                                                <td>{{ $teacher->user?->email }}</td>
                                                <td>{{ $teacher->department?->department_name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($teacher->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.users.toggleStatus', ['role' => 'teacher', 'id' => $teacher->id]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-{{ $teacher->status === 'active' ? 'warning' : 'success' }}">
                                                            {{ $teacher->status === 'active' ? 'Set Inactive' : 'Set Active' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Students</h5>
                    </div>
                    <div class="card-body">
                        @if($students->isEmpty())
                            <div class="alert alert-warning mb-0">
                                No students found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Program</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            <tr>
                                                <td>{{ $student->SFname }} {{ $student->SMname }} {{ $student->SLname }}</td>
                                                <td>{{ $student->user?->email }}</td>
                                                <td>{{ $student->program?->program_name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($student->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.users.toggleStatus', ['role' => 'student', 'id' => $student->student_id]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-{{ $student->status === 'active' ? 'warning' : 'success' }}">
                                                            {{ $student->status === 'active' ? 'Set Inactive' : 'Set Active' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
