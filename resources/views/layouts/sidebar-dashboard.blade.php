<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('home') }}">Gambung Store<br>
        <img src="{{ asset('assets/img/gambung_coklat.png') }}" width="180" alt="logo_gambung">
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('home') }}">GS</a>
    </div>
    <ul class="sidebar-menu">
      @if(Auth::user()->role == "ROLPJ")
      <li class="nav-item {{ ((Request::is('home')||Request::is('/')) ? 'active' : '') }}">
        <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
      </li>
      <li class="nav-item {{ (Request::is('mengelola-produk') ? 'active' : '') }}">
        <a href="{{ url('/mengelola-produk') }}" class="nav-link"><i class="fas fa-building"></i> <span>Mengelola Produk</span></a>
      </li>
      <li class="nav-item {{ (Request::is('chat') ? 'active' : '') }}">
        <a href="{{ url('/chat') }}" class="nav-link"><i class="fas fa-comment"></i> <span>Message</span></a>
      </li>
      <li class="nav-item dropdown {{ ((Request::is('kelola-pendapatan') || (Request::is('list-pesanan')) ) ? 'active' : '') }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-credit-card"></i> <span>Transaksi</span></a>
        <ul class="dropdown-menu">
          <li><a class="nav-link" href="{{ url('/list-pesanan') }}">List Pesanan</a></li>
        </ul>
      </li>
      @elseif(Auth::user()->role == "ROLAD" || Auth::user()->role == "ROLSA")
      <li class="nav-item {{ (Request::is('home') ? 'active' : '') }}">
        <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
      </li>
      <li class="nav-item dropdown {{ ((Request::is('manajemen-admin') || (Request::is('manajemen-penjual')) ) ? 'active' : '') }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-circle"></i> <span>Manajemen Akun</span></a>
        <ul class="dropdown-menu">
          @if(Auth::user()->role == "ROLSA")
          <li><a class="nav-link" href="{{ url('/manajemen-admin') }}">Manajemen Admin</a></li>
          @endif
          <li><a class="nav-link" href="{{ url('/manajemen-penjual') }}">Manajemen Penjual</a></li>
        </ul>
      </li>
      <li class="nav-item {{ (Request::is('mengelola-toko') ? 'active' : '') }}">
        <a href="{{ url('/mengelola-toko') }}" class="nav-link"><i class="fas fa-desktop"></i> <span>Mengelola Toko</span></a>
      </li>
      <li class="nav-item {{ (Request::is('mengelola-produk-admin') ? 'active' : '') }}">
        <a href="{{ url('/mengelola-produk-admin') }}" class="nav-link"><i class="fas fa-building"></i> <span>Mengelola Produk</span></a>
      </li>
      <li class="nav-item {{ (Request::is('mengelola-voucher') ? 'active' : '') }}">
        <a href="{{ url('/mengelola-voucher') }}" class="nav-link"><i class="fas fa-shopping-cart"></i> <span>Mengelola Voucher</span></a>
      </li>
      <li class="nav-item {{ (Request::is('mengelola-kategori') ? 'active' : '') }}">
        <a href="{{ url('/mengelola-kategori') }}" class="nav-link"><i class="fas fa-tag"></i> <span>Mengelola Kategori</span></a>
      </li>
      <li class="nav-item {{ (Request::is('mengelola-transaksi') ? 'active' : '') }}">
        <a href="{{ url('/mengelola-transaksi') }}" class="nav-link"><i class="fas fa-credit-card"></i> <span>Mengelola Transaksi</span></a>
      </li>
      @endif
    </ul>
  </aside>
</div>
