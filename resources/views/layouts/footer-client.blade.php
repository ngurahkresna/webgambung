<div class="row footer-client">
  <div class="col-xs-12 col-md-4">
    <div class="row">
      <img src="{{ asset('assets/img/gambung_putih.png') }}" alt="Logo Gambung" height="150">
    </div>
    <div class="row">
      <div class="col-12 text-center">
        <h2>Ikuti Kami</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-center social">
        <a href="#" target="_blank"><img src="{{ asset('assets/img/social/facebook.png') }}" alt="Facebook"></a>
        <a href="#" target="_blank"><img src="{{ asset('assets/img/social/instagram.png') }}" alt="Instagram"></a>
        <a href="#" target="_blank"><img src="{{ asset('assets/img/social/twitter.png') }}" alt="Twitter"></a>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-4">
    <h2>Kontak</h2>
    <ul>
      <li>Jl Raya Gambung RT 3 RW 8 Desa Mekarsari</li>
      <li><span class="fa fa-phone"></span> Muhammad Ramdan : +62 85886982768</li>
      <li><span class="fa fa-phone"></span> Syam Shahid : +62 85785852874</li>
    </ul>
  </div>
  <div class="col-xs-12 col-md-4">
    <h2>Menu</h2>
    <ul>
      <li><a href="/produk" class="text-decoration-none text-white">Produk</a></li>
      <li><a href="/kontak" class="text-decoration-none text-white">Kontak Kami</li>
        @if(!Auth::check())
        <li><a href="/login" class="text-decoration-none text-white">Masuk</li>
          @endif
        </ul>
      </div>

    </div>
