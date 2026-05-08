<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('Posts.Components.admin-styles')
</head>
<body>
    @include('Posts.Components.navba')

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="mb-1">Dashboard</h1>
                    <p class="text-muted mb-0">Welcome back, admin. Monitor your classes and users from one central dashboard.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('adminclass.index') }}" class="btn btn-primary">Manage Classes</a>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">Manage Users</a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="card-text">{{ $totalUsers ?? 0 }}</h2>
                            <span class="btn btn-primary">View Details</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('teacher.classes.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Teachers</h5>
                            <h2 class="card-text">{{ $totalTeachers ?? 0 }}</h2>
                            <span class="btn btn-primary">View Details</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Students</h5>
                            <h2 class="card-text">{{ $totalStudents ?? 0 }}</h2>
                            <span class="btn btn-primary">View Details</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="mt-5 mb-3">
            <h2 class="h4 fw-semibold">Analytics</h2>
            <p class="text-muted mb-0">Key metrics and trends for classes and student populations.</p>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">Student Population by Year Level</h5>
                                <p class="text-muted mb-0">Shows the number of students grouped by class year level.</p>
                            </div>
                        </div>
                        <canvas id="yearLevelChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">Class Comparison</h5>
                                <p class="text-muted mb-0">Compare the largest classes by enrolled student count.</p>
                            </div>
                        </div>
                        <canvas id="classComparisonChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">Class Status Breakdown</h5>
                                <p class="text-muted mb-0">Active vs inactive classes across the system.</p>
                            </div>
                        </div>
                        <canvas id="classStatusChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Quick Class Summary</h5>
                        <div class="row row-cols-2 gx-3 gy-3">
                            <div class="col">
                                <div class="border rounded-3 p-3 bg-light">
                                    <small class="text-uppercase text-secondary">Total Classes</small>
                                    <h3 class="mt-2">{{ $totalClasses ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-3 bg-light">
                                    <small class="text-uppercase text-secondary">Active Classes</small>
                                    <h3 class="mt-2">{{ $classesByStatus['active'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-3 bg-light">
                                    <small class="text-uppercase text-secondary">Inactive Classes</small>
                                    <h3 class="mt-2">{{ $classesByStatus['inactive'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-3 bg-light">
                                    <small class="text-uppercase text-secondary">Tracked Classes</small>
                                    <h3 class="mt-2">{{ $classesWithStudents->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const yearLabels = @json(array_keys($studentCountsByYear));
        const yearData = @json(array_values($studentCountsByYear));

        const classLabels = @json($classesWithStudents->map(fn($class) => $class->class_name . ' (' . ($class->year_level ?? 'N/A') . 'Y)'));
        const classData = @json($classesWithStudents->pluck('students_count'));

        const statusLabels = @json(array_keys($classesByStatus));
        const statusData = @json(array_values($classesByStatus));

        new Chart(document.getElementById('yearLevelChart'), {
            type: 'bar',
            data: {
                labels: yearLabels,
                datasets: [{
                    label: 'Students',
                    data: yearData,
                    backgroundColor: ['#4f46e5', '#0ea5e9', '#16a34a', '#f59e0b', '#9333ea'],
                    borderRadius: 8,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        new Chart(document.getElementById('classComparisonChart'), {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [{
                    label: 'Enrolled Students',
                    data: classData,
                    backgroundColor: '#2563eb',
                    borderRadius: 8,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: { legend: { display: false }, tooltip: { mode: 'nearest' } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        new Chart(document.getElementById('classStatusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#14b8a6', '#fb7185'],
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: context => `${context.label}: ${context.parsed}` } }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
