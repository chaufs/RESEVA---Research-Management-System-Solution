<nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('images/RMSLogo.png') }}" alt="Reseva logo" class="navbar-brand-logo">
      <span>Reseva Admin</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarAdmin">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item me-2">
          <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item me-2">
          <a class="nav-link {{ request()->routeIs('adminclass.index') ? 'active' : '' }}" href="{{ route('adminclass.index') }}">Class Management</a>
        </li>
        <li class="nav-item me-3">
          <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users</a>
        </li>
      </ul>
      <form action="{{ route('logout') }}" method="POST" class="navbar-logout-form d-flex">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm navbar-logout-btn">
          <img src="{{ asset('images/logout.png') }}" alt="Logout" class="navbar-logout-icon">
          <span>Logout</span>
        </button>
      </form>
     
    </div>
  </div>
</nav>
