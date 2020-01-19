@extends('layouts.client-layout')

@section('page', 'Toko Online')

@section('content')
    <div class="row header">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-xs-12 col-md-5">
                            <h1>Mari bergabung dengan <span style="color:#388A6B;">Gambung</span> Store</h1>
                            <p>Kualitas produk gambungstore adalah kualitas nomor 1. Dibuat dengan detail dan memastikan
                                quality control produk, agar customer dapat menikmati kualitas maksimal dari produk
                                tersebut.</p>
                            <a href="/produk" class="btn btn-success">Beli Sekarang</a>
                        </div>
                        <div class="col-7 text-center">
                            <img src="{{ asset('assets/img/carousel/1.png') }}" alt="image" height="75%" width="45%">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">

                </div>
                <div class="col-md-7">

                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="row produk-landing">
        <div class="col-12 text-center">
            <h1>Produk Kami</h1>
            <hr class="divider">
        </div>
        @isset($products)
            @foreach ($products as $product)
                <div class="col-xs-12 col-md-4">
                    <div class="image-product">
                        @if (isset($product->images[0]->image_name))
                            <img src="{{ asset('assets/img/products/') . $product->images[0]->image_name }}" alt="Produk">
                        @else
                            <img src="{{ asset('assets/img/products/product-1.jpg') }}" alt="Produk">
                        @endisset
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
        @endisset
    </div>
@endsection
