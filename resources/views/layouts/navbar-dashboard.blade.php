<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
  </form>
  <ul class="navbar-nav navbar-right">
    @if(Auth::user()->role == 'ROLPJ')
    <li class="dropdown dropdown-list-toggle notif" id="pesan" ><a href="#" data-toggle="dropdown" data-notif="message" class="nav-link nav-link-lg message-toggle notif"><i class="far fa-envelope"></i></a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right">
        <div class="dropdown-header">
          Pesan
        </div>
        <div class="dropdown-list-content dropdown-list-message">

        </div>
      </div>
    </li>
    @endif
    <li class="dropdown dropdown-list-toggle notif" id="notif" ><a href="#" data-toggle="dropdown" data-notif="notification" class="nav-link notification-toggle nav-link-lg notif"><i class="far fa-bell"></i></a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right">
        <div class="dropdown-header">
          Notifikasi
        </div>
        <div class="dropdown-list-content dropdown-list-icons">

        </div>
      </div>
    </li>
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      @if(Auth::user()->avatar != null)
      <img alt="image" src="{{ asset('assets/img/avatar/'.Auth::user()->avatar) }}" class="rounded-circle mr-1">
      @else
      <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
      @endif
      <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
      <div class="dropdown-menu dropdown-menu-right">
        @if(Auth::user()->role == "ROLSA")
        <div class="dropdown-title">Super Admin</div>
        @elseif(Auth::user()->role == "ROLAD")
        <div class="dropdown-title">Admin</div>
        @else
        <div class="dropdown-title">Penjual</div>
        @endif
        <a href="{{ url('/profile') }}" class="dropdown-item has-icon">
          <i class="far fa-user"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
    </div>
  </li>
</ul>
</nav>
