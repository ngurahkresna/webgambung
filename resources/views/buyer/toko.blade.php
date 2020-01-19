@extends('layouts.client-layout')

@section('page', 'Toko')

@section('content')
<div class="container-fluid page" style="padding:100px;">
  <div class="row image-header">
    <img src="{{ asset('assets/img/products/product-1.jpg') }}" alt="" width="100%" height="100%">
  </div>
  <div class="row ket-header">
    <div class="col-xs-12 col-lg-3">
      <img class="profile" src="{{ asset('assets/img/gambung_coklat.png') }}" height="150px" width="150px">
    </div>
    <div class="col-xs-12 col-lg-2">
      <h4 class="font-weight-bold page-title m-0" style="font-size:18px">{{ $store[0]->name }}</h4>
    </div>
    <div class="col-xs-12 col-lg-2 button-toko">
      <h4>{{ $store[0]->city }} <span class="fa fa-map-marker"></span></h4>
      <form action="{{ url('/hubungi-toko') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-outline-dark" value="{{ $store[0]->code }}" name="code">Hubungi Toko</button>
      </form>
    </div>
    <div class="col-xs-12 col-lg-5">
      <h4>Jasa Ekspedisi</h4>
      <div class="row">
        @foreach($store[0]->expedition as $exp)
        <img src="{{ asset('assets/img/expeditions/'.($exp->expedition_code == 'jne' ? 'jne.png' : 'tiki.png' )) }}" alt="" height="50" width="120">
        @endforeach
      </div>
    </div>
  </div>
  <div class="row main-toko">
    <div class="col-xs-12 col-lg-3 sidebar">
      <div class="row">
        <div class="col-12">
          <h3>Deskripsi</h3>
          <p>{{ $store[0]->description }}</p>
        </div>
        <div class="col-12">
          <h3>Alamat</h3>
          <p>{{ $store[0]->address_1 }}</p>
        </div>
        <div class="col-12">
          <h3>Contact</h3>
          <p>{{ $store[0]->phone_1 }}</p>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-lg-9 product">
      <h2>Product ></h2>
      <div class="row">
        @foreach($store[0]->product as $product)
          <div class="col-xs-12 col-md-4">
            <div class="image-product">
              <img src="{{ asset('assets/img/products/') . $product->images[0]->image_name }}" alt="{{ $product->name }}">
              <h2 class="title">{{ $product->name }}</h2>
              <h2 class="price">{{ number_format($product->price, 2) }}</h2>
            </div>
            <div class="overflow text-center">
              <div class="detail">
                <a href="/detail-produk/{{ $product->code }}" class="btn btn-outline-light">Rincian</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
