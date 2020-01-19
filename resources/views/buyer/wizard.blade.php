<div class="row wizard">
  <div class="col-xs-12 col-md-4">
    <div class="row">
      <div class="col-3 bulat {{ Request::is('checkout') ? 'active' : '' }} text-center">
        <h1>1</h1>
      </div>
      <div class="col-9">
        <h1>Pengisian Form</h1>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-4">
    <div class="row">
      <div class="col-3 bulat {{ Request::is('bayar/*') ? 'active' : '' }} text-center">
        <h1>2</h1>
      </div>
      <div class="col-9">
        <h1>Pembayaran</h1>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-4">
    <div class="row">
      <div class="col-3 bulat {{ Request::is('verifikasi/*') ? 'active' : '' }} text-center">
        <h1>3</h1>
      </div>
      <div class="col-9">
        <h1>Verifikasi</h1>
      </div>
    </div>
  </div>
</div>
