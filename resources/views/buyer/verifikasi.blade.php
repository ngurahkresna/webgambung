@extends('layouts.client-layout')

@section('page', 'Checkout Produk')

@section('content')
<div class="container-fluid page">
  <div class="row">
    <div class="col-12 page-title">
      <h2>CHECKOUT</h2>
    </div>
  </div>
  <div class="checkout-container">
    @include('buyer.wizard')
    <hr>
    @foreach($transactions[0]->detail as $detail)
    <div class="row">
      <div class="col-12 text-left">
        <h3>{{ $detail->product->name }}
          @isset($detail->status)
          <span class="badge badge-success">{{ $detail->status->value }}</span>
          @endisset
        </h3>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-lg-6">
        <p>Harga Produk</p>
      </div>
      <div class="col-xs-12 col-lg-6 text-right">
        <p class="font-weight-bold">Rp {{ number_format($detail->price, 2) }}</p>
      </div>
    </div>
    @endforeach
    <hr>
    <div class="row">
      <div class="col-xs-12 col-lg-6">
        <p>Discount</p>
      </div>
      <div class="col-xs-12 col-lg-6 text-right">
        <p>Rp {{ number_format($transactions[0]->discount_amount, 2) }}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-lg-6">
        <p>Biaya Jasa Ekspedisi</p>
      </div>
      <div class="col-xs-12 col-lg-6 text-right">
        <p>Rp {{ number_format($transactions[0]->shipping_charges, 2) }}</p>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-xs-12 col-lg-9 text-right">
        <h2>Total Harga</h2>
      </div>
      <div class="col-xs-12 col-lg-3 text-right">
        <h3>Rp <span class="text-danger font-weight-bold">{{ number_format($transactions[0]->grand_total_amount, 2) }}</span></h3>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-12">
        <h2>Verifikasi Pembayaran</h2>
      </div>
      <div class="col-12">
        <p>Transaksi anda sedang diproses, untuk mempercepat proses verifikasi
          silahkan hubungi toko. Anda juga dapat merubah bukti pembayaran dan tunggu hingga proses berhasil</p>
        </div>
      </div>
      <div class="row waktu text-center">
        <div class="col-12">
          <p>Sisa Waktu Verifikasi</p>
        </div>
        <div class="col-12 mb-5">
          <div id="timer" data-deadline="{{ $transactions[0]->payment->deadline_proof }}">
            <table class="text-center" align="center">
              <tr>
                <td><h2 class="text-danger" id="jam">00</h2></td>
                <td>:</td>
                <td><h2 class="text-danger" id="menit">00</h2></td>
                <td>:</td>
                <td><h2 class="text-danger" id="detik">00</h2></td>
              </tr>
              <tr>
                <td><p>Jam</p></td>
                <td></td>
                <td><p>Menit</p></td>
                <td></td>
                <td><p>Detik</p></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="col-12">
          <p>No Rekening</p>
        </div>
        <div class="col-12">
          <h2>0086197911100</h2>
        </div>
        <div class="col-12">
          <p>a/n</p>
        </div>
        <div class="col-12 mb-5">
          <h2>Bumdes Pakis Sabilulungan</h2>
        </div>
        <div class="col-12 text-left">
          <form class="" action="{{ url('/update-pembayaran') }}" method="post" enctype="multipart/form-data">
            @csrf
            <img src="{{ asset('/assets/img/proof/'.$transactions[0]->payment->proof_image) }}" alt="" width="100%" height="100%">
            <p>Update Bukti Bayar Disini : </p>
            <div class="input-group" style="z-index:0">
              <div class="custom-file">
                <input type="file" name="foto" class="custom-file-input" id="inputGroupFile04">
                <input type="text" name="transaction_code" value="{{ $transactions[0]->code }}" hidden>
                <label class="custom-file-label" for="inputGroupFile04">Pilih Bukti</label>
              </div>
              <div class="input-group-append">
                <button class="btn btn-success" type="submit">Upload</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endsection

  @push('script')
  <script type="text/javascript">
  var countDownDate = new Date($('#timer').data('deadline'));
  var x = setInterval(function() {

    var now = new Date().getTime();
    var distance = countDownDate - now;

    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    $('#jam').html(hours);
    $('#menit').html(minutes);
    $('#detik').html(seconds);

    //kalo deadline abis
    if (distance < 0) {
      clearInterval(x);
    }
  }, 1000);
  </script>
  <script type="text/javascript">
  $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });
  </script>
  @endpush
