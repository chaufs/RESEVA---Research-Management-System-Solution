<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Class Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('Posts.Components.admin-styles')
</head>
<body>
    @include('Posts.Components.teacher-nav')

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Teacher Class Management</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($classes->isEmpty())
            <div class="alert alert-warning">
                No classes are available yet.
            </div>
        @else
            @php
                $classesByProgram = $classes->groupBy(function ($class) {
                    return $class->program?->program_name ?? 'Unassigned Program';
                });
            @endphp

            @foreach($classesByProgram as $programName => $programClasses)
                <div class="mb-4">
                    <h2 class="h5 fw-semibold mb-3">{{ $programName }}</h2>
                    <div class="row g-4">
                        @foreach($programClasses as $class)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card h-100 shadow-sm overflow-hidden position-relative">
                                    <a href="{{ route('teacher.classes.show', $class) }}" class="stretched-link" aria-label="Manage groups for {{ $class->class_name }}"></a>
                                    <div class="bg-secondary bg-opacity-10" style="height: 180px; background: linear-gradient(135deg, rgba(52,88,255,0.16), rgba(52,136,255,0.05)), url('https://picsum.photos/seed/{{ $class->id }}/600/400'); background-size: cover; background-position: center; position: relative;">
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: rgba(0,0,0,0.22); backdrop-filter: blur(4px);">
                                            <span class="badge bg-white text-dark">{{ $class->year_level ? 'Year ' . $class->year_level : 'No Year' }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body pb-3">
                                        <h5 class="card-title mb-2">{{ $class->class_name }}</h5>
                                        <p class="card-text text-muted mb-2">{{ \Illuminate\Support\Str::limit($class->subject ?? 'No subject assigned', 40) }}</p>
                                        <p class="card-text small text-muted mb-3">{{ trim(($class->teacher?->firstname ?? '') . ' ' . ($class->teacher?->Lastname ?? '')) ?: 'No instructor assigned' }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-semibold">Open</span>
                                            <div class="d-flex gap-3 align-items-center text-muted">
                                                <span title="Details">&#9432;</span>
                                                <span title="Favorite">&#9734;</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-muted">{{ $class->students->count() }} students</span>
                                            <span class="badge bg-primary bg-opacity-10 text-primary">Open</span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
