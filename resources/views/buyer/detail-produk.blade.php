@extends('layouts.client-layout')

@section('page', 'Detail Produk')

@section('content')
<div class="container-fluid page">
  <div class="row">
    <div class="col-12 page-title">
      <h2>DETAIL PRODUK</h2>
    </div>
  </div>
  <div class="row container-detail-produk">
    <div class="col-xs-12 col-lg-6">
      <div class="main-image">
        @if(isset($product[0]->images[0]))
        <img src="{{ asset('assets/img/products') . $product[0]->images[0]->image_name }}"
        alt="{{ $product[0]->name }}" height="500px" width="100%">
        @else
        <img src="{{ asset('assets/img/products/product-1.jpg') }}"
        alt="{{ $product[0]->name }}" height="500px" width="100%">
        @endif
      </div>
      <div class="side-image">
        <div class="row mt-3">
          @foreach($product[0]->images as $image)
          @if($image->main_image == "OPTNO")
          <div class="col-3">
            <img src="{{ asset('assets/img/products') . $image->image_name }}"
            alt="{{ $product[0]->name }}" height="120px" width="100%">
          </div>
          @endif
          @endforeach
        </div>
      </table>
    </div>
  </div>
  <div class="col-xs-12 col-lg-6 detail-produk-content">
    <div class="row">
      <div class="col-xs-12 col-lg-10">
        <p><span class="font-weight-bold">{{ $wishlists }}x</span> Wishlist <span class="font-weight-bold">{{ $total_jual }}x</span> Terjual</p>
      </div>
      <div class="col-xs-12 col-lg-2">
        <form class="" action="{{ url('/proses-wishlist') }}" method="post">
          @csrf
          <button type="submit" class="btn" value="{{ $product[0]->code }}" name="code">
            <span class="fa fa-heart {{ $status_wishlist ? 'text-danger' : 'text-dark' }}"
            id="wishlist" style="font-size:25px;"></span>
          </button>
        </form>
      </div>
    </div>
    <form action="{{ route('cart.store') }}" method="POST">
      @csrf
      <input type="hidden" name="product_price" value="{{ $product[0]->price }}">
      <input type="hidden" name="product_code" value="{{ $product[0]->code }}">
      <div class="row">
        <div class="col-xs-12 col-lg-10">
          <h2 class="font-weight-bold pb-2" style="font-size:50px;">{{ $product[0]->name }}</h2>
          <p>All Available Stock : <span class="font-weight-bold">{{ $product[0]->stock }}</span> Pcs
          </p>
        </div>
        <div class="col-xs-12 col-lg-2">
          <div class="addthis_inline_share_toolbox m-0" style="float:left !important;"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-lg-12">
          <h1 id="harga" style="font-size:35px;">Rp {{ number_format($product[0]->price, 2) }},-</h1>
        </div>
      </div>
      @isset($product[0]->color)
      <div class="row">
        <div class="col-xs-12 col-lg-12">
          <p class="mt-3">Warna</p>
          <p class="font-weight-bold">{{ $product[0]->color }}</p>
        </div>
      </div>
      @endisset
      @isset($product[0]->product_detail[0])
      <div class="row" id="ukuran">
        <div class="col-xs-12 col-lg-12">
          <p class="mt-3">Ukuran</p>
          <ul class="nav nav-pills">
            <input type="text" name="ukuran" value="-" hidden>
            @foreach($product[0]->product_detail as $detail)
            <li class="nav-item m-1">
              <a class="nav-link text-success ukuran">{{ $detail->size }}</a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
      @endisset
      <div class="row d-none" id="status_stock">
        <div class="col-xs-12 col-lg-12">
          <p>Stock</p>
          <p><span class="font-weight-bold" id="stock">-</span> pcs</p>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <p>Kuantitas</p>
        </div>
        <div class="col-xs-12 col-lg-4">
          <div class="input-group">
            <span class="input-group-btn">
              <a class="btn btn-danger btn-number" data-type="minus" data-field="quant">
                <span class="fa fa-minus"></span>
              </a>
            </span>
            <input type="text" name="quant" class="form-control input-number text-center bg-transparent" value="1" min="1"
            max="{{ $product[0]->stock }}" id="banyak" readonly="true">
            <span class="input-group-btn">
              <a class="btn btn-success btn-number" data-type="plus" data-field="quant">
                <span class="fa fa-plus"></span>
              </a>
            </span>
          </div>
        </div>
        <div class="col-xs-12 col-lg-2">
          <button type="submit" name="button" class="btn btn-success"
          id="btn_beli" {{ isset($product[0]->product_detail[0]) ? 'disabled' : '' }}><span
          class="fa fa-shopping-cart"></span> Beli
        </button>
      </div>
    </div>
  </form>
  <div class="row">
    <div class="col-12">
      <div id="accordion">
        <div class="text-divided">
          <a class="link-collapse border-bottom border-success pb-2" data-toggle="collapse"
          href="#deskripsi" role="button"
          aria-expanded="true">Deskripsi Barang</a>
          <a class="link-collapse pb-2" data-toggle="collapse" href="#review" role="button"
          aria-expanded="false">Review</a>
        </div>
        <div class="collapse show" id="deskripsi" data-parent="#accordion">
          <div class="">
            {{ $product[0]->description }}
          </div>
        </div>
        <div class="collapse" id="review" data-parent="#accordion">
          <div class="overflow-auto" style="max-height:200px;">
            @foreach($product[0]->reviews as $review)
            <div class="row">
              <div class="col-xs-12 col-lg-2">
                <img src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                class="rounded-circle" height="60px" width="60px">
              </div>
              <div class="col-xs-12 col-lg-10 text-left pl-1">
                <h5>{{ $review->users->name }}</h5>
                <p style="font-size:13px;">{{ $review->review }}</p>
              </div>
            </div>
            @endforeach
          </div>
          @if($status_review == true)
          <form action="{{ url('/input-review') }}" method="post" class="pt-3">
            @csrf
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Masukan Review"
              name="review" required>
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit"
                value="{{ $product[0]->code }}" name="code" style="z-index:0">Submit
              </button>
            </div>
          </div>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>
</div>
</div>
<div class="row container-detail-produk pt-5">
  <div class="col-xs-12 col-lg-2">
    <img src="{{ asset('assets/img/gambung_coklat.png') }}" height="90%" width="100%">
  </div>
  <div class="col-xs-12 col-lg-3 store pr-5">
    <h4>{{ $product[0]->store->name }}</h4>
    <p>{{ $product[0]->store->city }} <span class="fa fa-map-marker"></span></p>
    <form action="{{ url('/hubungi-toko') }}" method="post">
      @csrf
      <button type="submit" class="btn btn-outline-dark" value="{{ $product[0]->store->code }}"
        name="code">Hubungi Toko
      </button>
      <a href="{{ url('/toko/'.$product[0]->store->code) }}" class="btn btn-light">Info Toko</a>
    </form>
  </div>
  <div class="col-xs-12 col-lg-7 text-left col-trans pl-5">
    <h5>Jasa Ekspedisi</h5>
    @foreach($product[0]->store->expedition as $exp)
    <img
    src="{{ asset('assets/img/expeditions/'.($exp->expedition_code == 'jne' ? 'jne.png' : 'tiki.png' )) }}"
    alt="" height="80px" width="130px">
    @endforeach
  </div>
</div>
@isset($products)
<div class="row suggest mx-5">
  <div class="col-12 text-center page-title m-0">
    <h2>Hanya Untuk Anda</h2>
  </div>
  @foreach ($products as $product)
  <div class="col-xs-12 col-md-3">
    <div class="image-product">
      @if(isset($product->images[0]))
      <img src="{{ asset('assets/img/products/') . $product->images[0]->image_name }}"
      alt="Produk" style="height:250px;">
      @else
      <img src="{{ asset('assets/img/products/product-1.jpg') }}" alt="Produk"
      style="height:250px;">
      @endif
      <h2 class="title">{{ $product->name }}</h2>
      <h2 class="price">{{ number_format($product->price, 2) }}</h2>
    </div>
    <div class="overflow text-center" style="padding-top: 300px;">
      <div class="detail">
        <a href="/detail-produk/{{ $product->code }}" class="btn btn-outline-light">Rincian</a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endisset
</div>
@endsection

@push('script')

<script type="text/javascript">

$('.ukuran').on('click', function () {
  $('.ukuran').removeClass('active');
  $(this).addClass('active');
  $('input[name="ukuran"]').val($(this).text());
  showStock();
});

function showStock(){
  $("#status input").each(function() {
    if ($(this).val() == "-"){
      return false;
    }
  });
  var product_code = $('input[name="product_code"]').val();
  var size = $('input[name="ukuran"]').val();
  $.ajax({
    type: 'GET',
    url: '/get-stock-status/'+product_code+'/'+size,
    success: function (response) {
      console.log(response);
      $("#banyak").attr('max', response);
      $("#status_stock").removeClass('d-none');
      $("#stock").text(response);
      if (response > 0) {
        $("#btn_beli").removeAttr('disabled');
      }else{
        $("#btn_beli").attr('disabled', 'true');
      }
    }
  });

}

$('.btn-number').click(function (e) {
  e.preventDefault();

  fieldName = $(this).attr('data-field');
  type = $(this).attr('data-type');
  var input = $("input[name='" + fieldName + "']");
  var currentVal = parseInt(input.val());

  if (!isNaN(currentVal)) {
    if (type == 'minus') {

      if (currentVal > input.attr('min')) {
        input.val(currentVal - 1).change();
      }
      if (parseInt(input.val()) == input.attr('min')) {
        $(this).attr('disabled', true);
      }

    } else if (type == 'plus') {

      if (currentVal < input.attr('max')) {
        input.val(currentVal + 1).change();
      }
      if (parseInt(input.val()) == input.attr('max')) {
        $(this).attr('disabled', true);
      }

    }
  } else {
    input.val(0);
  }
});

$('.input-number').focusin(function () {
  $(this).data('oldValue', $(this).val());
});

$('.input-number').change(function (e) {

  minValue = parseInt($(this).attr('min'));
  maxValue = parseInt($(this).attr('max'));
  valueCurrent = parseInt($(this).val());

  name = $(this).attr('name');
  if (valueCurrent >= minValue) {
    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
  } else {
    alert('Sorry, the minimum value was reached');
    $(this).val($(this).data('oldValue'));
  }
  if (valueCurrent <= maxValue) {
    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
  } else {
    Swal.fire({
      title: "Error",
      type: "error",
      showConfirmButton: true,
      showCloseButton: false,
      allowEscapeKey: false,
      allowOutsideClick: false
    }).then((result) => {
      $(this).submit(function(e){
        e.preventDefault();
      });
    });
  }
});

$(".input-number").on('submit', function(){

});

$(".input-number").keydown(function (e) {
  // Allow: backspace, delete, tab, escape, enter and .
  if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
  // Allow: Ctrl+A
  (e.keyCode == 65 && e.ctrlKey === true) ||
  // Allow: home, end, left, right
  (e.keyCode >= 35 && e.keyCode <= 39)) {
    // let it happen, don't do anything
    return;
  }
  // Ensure that it is a number and stop the keypress
  if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
    e.preventDefault();
  }
});
</script>

<script type="text/javascript">
$('.side-image img').on('click', function () {
  var mainImage = $('.main-image img').attr('src');
  var sideImage = $(this).attr('src');
  $(this).attr('src', mainImage);
  $('.main-image img').attr('src', sideImage);
});

$('.link-collapse').on('click', function () {
  var status = $(this).attr('aria-expanded');
  $('.link-collapse').removeClass('border-bottom border-success');
  if (status == "false") {
    $(this).addClass('border-bottom border-success');
  }
});

</script>

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5df8f06cbc0040bb"></script>

@endpush
