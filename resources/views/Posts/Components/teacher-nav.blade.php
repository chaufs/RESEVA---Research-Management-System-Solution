<nav class="navbar navbar-expand-lg navbar-light sticky-top py-3" style="background:#fff !important; border-bottom:1px solid rgba(15,23,42,0.08); box-shadow:0 8px 24px rgba(15,23,42,0.04);">
  <div class="container-fluid px-3 px-lg-4">
    <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('teacher.dashboard') }}" style="font-weight:700; letter-spacing:0.2px; color:#111827 !important;">
      <img src="{{ asset('images/RMSLogo.png') }}" alt="Reseva logo" style="width:42px; height:42px; object-fit:contain; border-radius:0.85rem; background:rgba(52,88,255,0.08); padding:0.35rem; border:1px solid rgba(52,88,255,0.12);">
      <span>Reseva Teacher</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTeacher" aria-controls="navbarTeacher" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTeacher">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
        <li class="nav-item">
          <a class=" nav-link px-3 {{ request()->routeIs('teacher.dashboard') ? 'active fw-semibold text-primary' : 'text-dark' }}" href="{{ route('teacher.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link px-3 {{ request()->routeIs(['teacher.classes.index', 'teacher.classes.show']) ? 'active fw-semibold text-primary' : 'text-dark' }}" href="{{ route('teacher.classes.index') }}">My Classes</a>
        </li>
        <li class="nav-item ms-lg-2">
          <form action="{{ route('logout') }}" method="POST" class="d-flex">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm px-3" style="border-color:rgba(107,114,128,0.25); color:#374151;">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>