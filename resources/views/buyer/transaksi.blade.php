@extends('layouts.client-layout')

@section('page', 'Cart Produk')

@section('content')
<div class="container-fluid page">
  <div class="row">
    <div class="col-12 page-title">
      <h2>SHOPPING CART</h2>
    </div>
  </div>
  <div class="row container-transaksi">
    <div class="col-12">
      <div id="accordion">
        <div class="row text-divided text-center">
          <a class="col-6 link-collapse border-bottom border-success pb-2" data-toggle="collapse" href="#berlangsung" role="button" aria-expanded="true">
            <h2>Sedang Berlangsung</h2>
          </a>
          <a class="col-6 link-collapse pb-2" data-toggle="collapse" href="#histori" role="button" aria-expanded="false">
            <h2>History</h2>
          </a>
        </div>
        <div class="collapse show" id="berlangsung" data-parent="#accordion">
          @foreach($transactions as $transaction)
          @foreach($transaction->detail as $detail)
          @if($detail->shipping_status != 'OPTRC' && $detail->shipping_status != 'OPTCC')
          <div class="row trans py-3 mb-3">
            <div class="col-12">
              <p>{{ $detail->created_at }}</p>
            </div>
            <div class="col-xs-12 col-lg-3">
              <a href="{{ url('/detail-produk/'.$detail->product->code) }}">
                <div class="row">
                  <div class="col-xs-12 col-lg-4 p-2">
                    <img src="{{ asset('assets/img/products/'.$detail->product->images[0]->image_name) }}" alt="" height="100" width="100">
                  </div>
                  <div class="col-xs-12 col-lg-8">
                    <h4>{{ $detail->product->name }}</h4>
                    <p>Rp
                      <span class="font-weight-bold">{{ number_format($detail->product->price, 2) }}</span>
                      <span style="font-size:10px;">/pcs</span>
                    </p>
                    <p>Total Harga Produk</p>
                    <p>Rp
                      <span class="font-weight-bold text-danger">{{ number_format($detail->product->price*$detail->quantity, 2) }}</span>
                      <span style="font-size:10px;">({{ $detail->quantity*100/100 }}pcs)</span>
                    </p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xs-12 col-lg-2 text-center col-trans">
              <h4>Jasa Ekspedisi</h4>
              <p>{{$detail->expedition}}</p>
            </div>
            <div class="col-xs-12 col-lg-2 store col-trans">
              <h4>{{ $detail->product->store->name }}</h4>
              <p>{{ $detail->product->store->address_1 }} <span class="fa fa-map-marker"></span></p>
              <form action="{{ url('/hubungi-toko') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-outline-dark" value="{{ $detail->product->store->code }}" name="code">Hubungi Toko</button>
                <a href="{{ url('/toko/'.$detail->product->store->code) }}" class="btn btn-light">Info Toko</a>
              </form>
            </div>
            <div class="col-xs-12 col-lg-5 col-trans">
              <div class="row">
                <div class="col-9">
                  <h4>Status</h4>
                  <h3>
                    @if($transaction->payment->updated_process == 'pembayaran')
                    Proses Pembayaran
                    @elseif($transaction->payment->updated_process == 'verifikasi')
                    Proses Verifikasi
                    @else
                    Proses Pengiriman
                    @endif
                  </h3>
                  @if($transaction->payment->updated_process == 'pembayaran')
                  <p>{{ $transaction->code }}</p>
                  @else
                  <p>{{ $transaction->detail[0]->shipping_no }}</p>
                  @endif
                </div>
                <div class="col-3">
                  @if($transaction->payment->updated_process == 'pembayaran')
                  <a href="{{ url('/bayar/'.$transaction->code) }}" class="btn btn-outline-dark">Bayar Sekarang</a>
                  <form class="mt-2" action="/transaksi/cancel" method="post">
                    @csrf
                    <input type="text" name="product_code" value="{{ $detail->product->code }}" hidden>
                    <input type="text" name="transaction_code" value="{{ $transaction->code }}" hidden>
                    <button type="submit" class="btn btn-outline-danger" style="width:100%">Cancel</button>
                  </form>
                  @elseif($transaction->payment->updated_process == 'verifikasi')
                  <a href="{{ url('/verifikasi/'.$transaction->code) }}" class="btn btn-outline-dark">Ubah Bukti Transfer</a>
                  @else
                  <form class="" action="{{ url('/transaksi/terima') }}" method="post">
                    <input type="text" name="product_code" value="{{ $detail->product->code }}" hidden>
                    <input type="text" name="transaction_code" value="{{ $transaction->code }}" hidden>
                    @csrf
                    <button type="submit" href="/toko" class="btn btn-success" style="width:100%">Diterima</button>
                  </form>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endif
          @endforeach
          @endforeach
        </div>
        <div class="collapse" id="histori" data-parent="#accordion">
          @foreach($transactions as $transaction)
          @foreach($transaction->detail as $detail)
          @if($detail->shipping_status != 'OPTNO' && $detail->shipping_status != 'OPTSD')
          <div class="row trans py-3 mb-3">
            <div class="col-12">
              <p>{{ $detail->created_by }}</p>
            </div>
            <div class="col-xs-12 col-lg-3">
              <a href="{{ url('/detail-produk/'.$detail->product->code) }}">
                <div class="row">
                  <div class="col-xs-12 col-lg-4 p-2">
                    <img src="{{ asset('assets/img/products/'.$detail->product->images[0]->image_name) }}" alt="" height="100" width="100">
                  </div>
                  <div class="col-xs-12 col-lg-8">
                    <h4>{{ $detail->product->name }}</h4>
                    <p>Rp
                      <span class="font-weight-bold">{{ number_format($detail->product->price, 2) }}</span>
                      <span style="font-size:10px;">/pcs</span>
                    </p>
                    <p>Total Harga Produk</p>
                    <p>Rp
                      <span class="font-weight-bold text-danger">{{ number_format($detail->product->price*$detail->quantity, 2) }}</span>
                      <span style="font-size:10px;">({{ $detail->quantity*100/100 }}pcs)</span>
                    </p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xs-12 col-lg-2 text-center col-trans">
              <h4>Jasa Ekspedisi</h4>
              <p>{{$detail->expedition}}</p>
            </div>
            <div class="col-xs-12 col-lg-2 store col-trans">
              <h4>{{ $detail->product->store->name }}</h4>
              <p>{{ $detail->product->store->address_1 }} <span class="fa fa-map-marker"></span></p>
              <form action="{{ url('/hubungi-toko') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-outline-dark" value="{{ $detail->product->store->code }}" name="code">Hubungi Toko</button>
                <a href="{{ url('/toko/'.$detail->product->store->code) }}" class="btn btn-light">Info Toko</a>
              </form>
            </div>
            <div class="col-xs-12 col-lg-5 col-trans">
              <div class="row">
                <div class="col-9">
                  <h4>Status</h4>
                  <h3>
                    @if($detail->shipping_status == 'OPTRC')
                    <span class="text-success">Berhasil</span>
                    @else
                    <span class="text-danger">Ditolak</span>
                    @endif
                  </h3>
                  <p>{{ $transaction->detail[0]->shipping_no }}</p>
                </div>
              </div>
            </div>
          </div>
          @endif
          @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
$('.link-collapse').on('click', function(){
  var status = $(this).attr('aria-expanded');
  $('.link-collapse').removeClass('border-bottom border-success');
  if (status == "false") {
    $(this).addClass('border-bottom border-success');
  }
});
</script>
@endpush
