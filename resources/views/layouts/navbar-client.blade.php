<nav class="navbar navbar-expand-lg main-navbar navbar-light sticky-top">
  <a class="navbar-brand" href="/">
    <img src="{{ asset('assets/img/gambung_coklat.png') }}" alt="logo gambung" height="70">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="/produk">Produk</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/kontak">Kontak Kami</a>
      </li>
      @if(Auth::user() != null)
      <li class="nav-item notif" id="notif-transaksi" data-notif="notification">
        <div class="dropdown show">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-bell"></span>
            <span class="badge d-none infonotif">n</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          </div>
        </div>
      </li>
      @endif
      @if(Auth::user() != null)
      <li class="nav-item notif" id="notif-message" data-notif="message">
        <div class="dropdown show">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fa fa-envelope"></span>
            <span class="badge d-none infonotif">n</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          </div>
        </div>
      </li>
      @endif
      <li class="nav-item">
        @if(Auth::user() == null)
        <a class="nav-link" href="/login">Masuk</a>
        @else
        <div class="dropdown show">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle"></span> {{ Auth::user()->name }}</a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="/profile">
              <span class="fa fa-user-circle"></span> {{ Auth::user()->name }}
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/profile">Edit Profile</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/transaksi">Transaksi</a>
            <a class="dropdown-item" href="/wishlist">Wishlist</a>
            <a class="dropdown-item" href="/cart">Cart</a>
            <a class="dropdown-item" href="/checkout">Checkout</a>
            <a class="dropdown-item" href="/chat">Chat</a>
            <a class="dropdown-item" href="#" onclick="event.preventDefault();
            $('#logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>
        @endif
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0 search" action="{{ url('/search') }}" method="post">
      @csrf
      <button class="btn fa fa-search" type="submit"></button>
      <input class="form-control mr-sm-2" type="search" placeholder="Search.." name="key">
    </form>
  </nav>
