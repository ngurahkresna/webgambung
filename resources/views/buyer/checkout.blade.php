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
    <form action="{{ url('/proses-checkout') }}" method="post" id="form-checkout">
      @csrf
      <input type="number" name="total_produk" value="" hidden>
      <input type="number" name="total_ekspedisi" value="" hidden>
      <input type="number" name="total_diskon" value="" hidden>
      <input type="number" name="grand_total" value="" hidden>
      <input type="text" name="status_voucher" value="invalid" hidden>
      <div class="form-group row">
        <label class="col-sm-10">Alamat Pengiriman</label>
        {{--                    <div class="col-sm-2 text-right"><a>Ubah Alamat</a></div>--}}
        <div class="col-sm-12">
          <textarea type="text" class="form-control" name="address" rows="4"
          placeholder="Alamat Anda.." required>{{ $user->address_1 }}</textarea>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-12">Kota</label>
        <div class="col-sm-12">
          <select class="form-control" name="city" id="city" required>
            <option value="">Pilih Kota</option>
            @isset($cities)
            @foreach($cities as $city)
            <option value="{{ $city['city_id'] }}" {{ $city['city_id'] == $user->city ? "selected" : "s" }}>{{ $city['city_name'] }}</option>
            @endforeach
            @endisset
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-12">Nomor Telepon</label>
        <div class="col-sm-12">
          <input type="text" class="form-control" name="phone" rows="4" placeholder="Telepon Anda.."
          value="{{ $user->phone }}" required>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-12">Kode Voucher</label>
        <div class="col-sm-12">
          <input type="text" class="form-control" name="voucher" rows="4" placeholder="Optional" id="voucher">
          <div id="voucher-feedback" class="invalid-feedback mt-2"></div>
        </div>
      </div>
      @isset($stores)
      @foreach($stores as $store)
      <div class="row">
        <div class="col-xs-12 col-lg-6 mb-5 detail">
          <div class="row">
            <div class="col-12">
              <h2>{{ $store->name }}</h2>
              <p>{{ $store->address_1 }} <span class="fa fa-map-marker"></span>
              </p>
            </div>
          </div>
          @isset($store->product)
          @foreach($store->product as $product)
          @isset($product->cart)
          @foreach($product->cart as $cart)
          @if($cart->username == Auth::user()->username)
          <div class="row mt-2">
            <div class="col-xs-12 col-lg-5 text-center">
              @if (isset($cart->product->images[0]->image_name))
              <img
              src="{{ asset('assets/img/products') . $product->images[0]->image_name }}"
              alt="Image 1" height="200px" width="100%">
              @else
              <img src="{{ asset('assets/img/products/product-1.jpg') }}"
              alt="Produk"
              height="100px" width="100px">
              @endisset
            </div>
            <div class="col-xs-12 col-lg-7 mt-2">
              <h1>{{ $product->name }}</h1>
              <p>
                @isset($cart->product->color)
                <span class="badge badge-light">{{ $cart->product->color }} </span>
                @endisset
                @isset($cart->cart_product_status)
                <span class="badge badge-success">{{ $cart->cart_product_status->value }}</span> -
                @endisset
                <span>{{ $cart->quantity }}pcs</span>
              </p>
              <p>
                <span>Rp {{ number_format($product->price, 2) }}</span>/pcs
              </p>
            </div>
            <div class="col-12">
              <p>{{ $cart->message }}</p>
            </div>
          </div>
          @endif
          @endforeach
          @endisset
          @endforeach
          @endisset
        </div>
        <div class="col-xs-12 col-lg-6">
          <div class="form-group" style="margin:0px 30px;">
            <input type="text" name="expedition[]" value="" id="expedition_text_{{ $store->id }}" hidden>
            <input type="text" name="store_id[]" value="{{ $store->id }}" hidden>
            <select class="form-control expedition"
            id="expedition_{{ $store->id }}"
            data-total-weight="{{ $weight[$store->id] }}" required>
            <option value="-" selected>Pilih Ekspedisi</option>
          </select>
          <p>Perkiraan Lama Pengiriman : <span id="lama"></span></p>
        </div>
      </div>
    </div>
    @endforeach
    @endisset
    <hr>
    <div class="row rincian-harga">
      <div class="col-12">
        <h2>Rincian Harga</h2>
      </div>
      <div class="col-10">
        <p>Harga Produk</p>
      </div>
      <div class="col-2 text-right">
        <p data-harga="{{ $harga }}" id="biaya_produk">Rp {{ number_format($harga) }},- </p>
      </div>
      <div class="col-10">
        <p>Diskon</p>
      </div>
      <div class="col-2 text-right">
        <p data-harga="0" id="biaya_voucher">-</p>
      </div>
      <div class="col-10">
        <p>Biaya Jasa Ekspedisi</p>
      </div>
      <div class="col-2 text-right">
        <p data-harga="0" id="biaya_ekspedisi">-</p>
      </div>
    </div>
    <hr>
    <div class="row rincian-harga">
      <div class="col-9">
        <h2>Total Harga</h2>
      </div>
      <div class="col-3 text-right">
        <h2 id="total_biaya" data-harga="{{ $harga }}">Rp {{ number_format($harga) }},- </h2>
      </div>
    </div>
    <hr>
    <div class="row rincian-harga">
      <div class="col-12 text-right">
        <button type="submit" name="button" class="btn btn-success" >Checkout</button>
      </div>
    </div>
  </form>
</div>
</div>
@endsection

@push('script')
<script type="text/javascript">

$( document ).ready(function(){
  get_expedition();
});

//belum dicek
$("#form-checkout").submit(function(e){
  $(".expedition option:selected").each(function() {
    if($(this).data('store') == "-"){
      Swal.fire({
        title: "Error",
        text: "Silahkan pilih kurir terlebih dahulu",
        type: "error",
        showConfirmButton: true,
        showCloseButton: false,
        allowEscapeKey: false,
        allowOutsideClick: false
      }).then((result) => {
        e.preventDefault();
        return false;
      });
      e.preventDefault();
      return false;
    }
  });
});

function hitungTotal(){
  var total_produk = parseInt($("#biaya_produk").data('harga'));
  var total_ekspedisi = parseInt($("#biaya_ekspedisi").data('harga'));
  var voucher = parseInt($("#biaya_voucher").data('harga'));
  //console.log(total_produk + " " + total_ekspedisi + " " + voucher);
  var total = total_produk + total_ekspedisi - voucher;
  $("input[name='total_produk']").val(total_produk);
  $("input[name='total_ekspedisi']").val(total_ekspedisi);
  $("input[name='total_diskon']").val(voucher);
  $("input[name='grand_total']").val(total);
  $("#total_biaya").empty();
  $("#total_biaya").data('harga', total);
  $("#total_biaya").text("Rp "+formatNumber(total)+",-");
}

function resetEkspedisi(){
  $('.expedition').empty();
  $('.expedition').append('<option data-store="-" data-lama="-" value="0" selected>Pilih Ekspedisi</option>');
  $('#biaya_ekspedisi').text("-");
  $('#biaya_ekspedisi').data('harga', 0);
  hitungTotal();
}

// $('#province').change(function () {
//   var province = $(this).val();
//
//   $.ajax({
//     type: 'GET',
//     url: '/get-city/' + province,
//     dataType: 'json',
//     success: function (response) {
//       $('#city option').remove();
//       $('#city').append('<option value="">Pilih Kota</option>');
//       resetEkspedisi();
//       if (response.length > 0) {
//         $(response).each(function (i) {
//           $('#city').append('<option value="' + response[i].city_id + '">' + response[i].city_name + '</option>');
//         })
//       }
//     }
//   });
// });

function get_expedition(){
  var city = $("#city").val();
  $.ajax({
    type: 'POST',
    url: '/get-price',
    data: {city: city},
    success: function (response) {
      console.log(response);
      if (response.length > 0) {
        resetEkspedisi();
        $(response).each(function (i) {
          $(response[i].expedition).each(function (j) {
            $(response[i].expedition[j]["0"].costs).each(function (k) {
              $('#expedition_'+response[i].id).append('<option value="' + response[i].expedition[j]["0"].costs[k].cost["0"].value + '" data-lama="'+response[i].expedition[j]["0"].costs[k].cost["0"].etd + ' Hari" >' + response[i].expedition[j]["0"].name +" - "+ response[i].expedition[j]["0"].costs[k].service +'</option>');
              $('#expedition_'+response[i].id).data('store',response[i].id);
            })
          })
        })
      }
    }
  });
}

$('#city').change(function () {
  get_expedition();
});

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

$('.expedition').change(function () {
  var hargaOngkir = 0;
  var store_id = $(this).data('store');
  $('#expedition_text_'+store_id).val($("#expedition_"+store_id+" option:selected").html());
  $('#lama').text($("#expedition_"+store_id+" option:selected").data('lama'));

  console.log(store_id);
  console.log($('#expedition_text_'+store_id).val());

  $(".expedition option:selected").each(function() {
    hargaOngkir += parseInt($(this).val());
  });
  console.log(hargaOngkir);
  $('#biaya_ekspedisi').empty();
  $('#biaya_ekspedisi').text("Rp "+formatNumber(hargaOngkir)+",-");
  $('#biaya_ekspedisi').data('harga', parseInt(hargaOngkir));
  hitungTotal();
});

$('#voucher').on('input', function(){
  var voucherCode = $('#voucher').val();
  var biayaProduk = $('#biaya_produk').data('harga');
  if (voucherCode.length >= 6) {
    $.ajax({
      type: 'POST',
      url: '/check-voucher',
      data: {code: voucherCode,price: biayaProduk},
      success: function (response) {
        console.log(response);
        resetVoucher();
        $('#voucher').removeClass('is-invalid', 'text-success');
        if (response.status == "wrong code") {
          $('#voucher').addClass('is-invalid');
          $('#voucher-feedback').addClass('invalid-feedback');
          $('#voucher-feedback').text('Kode voucher tidak ditemukan');
        }else if (response.status == "wrong terms"){
          $('#voucher').addClass('is-invalid');
          $('#voucher-feedback').removeClass('text-success');
          $('#voucher-feedback').addClass('invalid-feedback');
          $('#voucher-feedback').text('Tidak dapat menggunakan voucher pada pembelanjaan ini');
        }else{
          $('#voucher').addClass('is-valid');
          $('#voucher-feedback').removeClass('invalid-feedback');
          $('#voucher-feedback').addClass('text-success');
          $('#voucher-feedback').text('anda mendapatkan '+response.tipe+' '+response.percentage+'% sebesar Rp '+formatNumber(response.voucher)+',- untuk toko '+response.stores);
          $("#biaya_voucher").data('harga', response.voucher);
          $("#biaya_voucher").text('Rp '+formatNumber(response.voucher)+',-');
          $('input[name="status_voucher"]').val('valid');
        }
        hitungTotal();
      }
    });
  }else if (voucherCode.length < 6){
    resetVoucher();
  }
});

function resetVoucher(){
  $("#biaya_voucher").data('harga', 0);
  $("#biaya_voucher").text("-");
  $('#voucher').removeClass('is-invalid', 'is-valid');
  $('#voucher-feedback').removeClass('text-success');
  $('#voucher-feedback').addClass('invalid-feedback');
  $('#voucher-feedback').text('');
  $('input[name="status_voucher"]').val('invalid');
}

</script>
@endpush
