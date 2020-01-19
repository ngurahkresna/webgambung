@extends('layouts.client-layout')

@section('page', 'Wishlist')

@section('content')
    <div class="row produk">
        <div class="col-12 text-center">
            <h1>Wishlist</h1>
            <hr class="divider">
        </div>
        @isset($categories)
            @foreach($categories as $category)
                @if($category->product != "[]")
                <div class="col-12 kategori">
                    <h2>{{ $category->name }} ></h2>
                </div>
                    @foreach($category->product as $product)
                        @foreach($product->wishlists as $wishlist)
                          @if($wishlist->id_users == Auth::user()->id)
                          <div class="col-xs-12 col-md-3">
                              <div class="image-product">
                                  @if (isset($product->images[0]->image_name))
                                      <img src="{{ asset('assets/img/products') . $product->images[0]->image_name }}" alt="Produk" height="100px" width="100px">
                                  @else
                                      <img src="{{ asset('assets/img/products/product-1.jpg') }}" alt="Produk" height="100px" width="100px">
                                  @endisset
                                  <h2 class="title">{{ $product->name }}</h2>
                                  <h2 class="price">{{ number_format($product->price, 2) }}</h2>
                              </div>
                              <div class="overflow text-center">
                                  <div class="detail">
                                      <a href="/detail-produk/{{ $product->code }}"
                                         class="btn btn-outline-light">Rincian</a>
                                  </div>
                              </div>
                          </div>
                          @endif
                        @endforeach
                    @endforeach
                @endif
            @endforeach
        @endisset
    </div>
@endsection
