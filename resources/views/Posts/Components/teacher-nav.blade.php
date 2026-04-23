<nav class="navbar navbar-expand-lg navbar-custom navbar-dark ">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('teacher.dashboard') }}">
      <img src="{{ asset('images/RMSLogo.png') }}" alt="Reseva logo" class="navbar-brand-logo">
      <span>Reseva Teacher</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTeacher" aria-controls="navbarTeacher" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTeacher">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item me-2">
          <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item me-3">
          <a class="nav-link {{ request()->routeIs(['teacher.classes.index', 'teacher.classes.show']) ? 'active' : '' }}" href="{{ route('teacher.classes.index') }}">My Classes</a>
        </li>
      </ul>
      <form action="{{ route('logout') }}" method="POST" class="navbar-logout-form d-flex">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm navbar-logout-btn">Logout</button>
      </form>
    </div>
  </div>
</nav>
