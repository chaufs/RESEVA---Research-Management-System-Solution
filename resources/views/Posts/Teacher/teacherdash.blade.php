<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('Posts.Components.admin-styles')
    <title>Teacher Dasboard</title>
</head>
<body>
    
   @include('Posts.Components.teacher-nav');

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Teacher Dashboard</h1>
            <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary">View My Classes</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

<!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <a href = "{{ route('teacher.classes.index') }}" style="text-decoration:none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Classes Assigned</p>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> All active</small>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <h2 class="card-title text-primary mb-0">{{ $totalClasses }}</h2>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Total Students</p>
                            <small class="text-info">Across all classes</small>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <h2 class="card-title text-success mb-0">{{ $totalStudents }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Tasks Assigned</p>
                            <small class="text-warning">Total tasks given</small>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <h2 class="card-title text-info mb-0">{{ $totalTasks ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Pending Reviews</p>
                            <small class="text-danger">Needs grading</small>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <h2 class="card-title text-warning mb-0">{{ $pendingSubmissions ?? 0 }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes by Program -->
        @if($classesByProgram->isNotEmpty())
            <div class="row g-4">
                @foreach($classesByProgram as $programName => $programClasses)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ $programName }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($programClasses as $class)
                                        <div class="col-12">
                                            <div class="card h-100 border overflow-hidden">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-auto p-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="min-width: 90px;">
                                                        <span class="fs-4 text-primary">{{ strtoupper(substr($class->class_name, 0, 1)) }}</span>
                                                    </div>
                                                    <div class="col">
                                                        <div class="card-body py-3 px-4">
                                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                                                <div>
                                                                    <h6 class="card-title mb-1">{{ $class->class_name }}</h6>
                                                                    <p class="card-text small text-muted mb-0">Year {{ $class->year_level }}</p>
                                                                </div>
                                                                <span class="badge bg-secondary py-2 px-3">{{ $class->students->count() }} students</span>
                                                            </div>
                                                            @if($class->subject)
                                                                <p class="card-text small text-muted mb-0 mt-2">
                                                                    <strong>Subject:</strong> {{ $class->subject }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center mt-5">
                <div class="card">
                    <div class="card-body py-5">
                        <h4 class="text-muted">No Classes Assigned</h4>
                        <p class="text-muted">You don't have any classes assigned yet. Contact your administrator.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
