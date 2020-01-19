@extends('layouts.dashboard-layout')

@section('page', 'Mengelola Produk')

@push('css')
    <link rel="stylesheet" href="{{ asset('node_modules/prismjs/themes/prism.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4></h4>
                    <div class="card-header-action">
                        <form>
                            <div class="input-group">
                                <button type="button" class="btn btn-primary btn-tambah" data-toggle="modal"
                                        data-target="#modal-tambah" name="tambah"><i class="fas fa-plus"></i> Tambah
                                    Produk
                                </button>
                                <input type="text" name="value" class="form-control" id="search" placeholder="Search">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive gambung-tables">
                        <table class="table table-striped" id="list">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Stock</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($produk as $pr)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $pr->name }}
                                    </td>
                                    <td>
                                        <div class="badge badge-primary">{{ $pr->stock }}</div>
                                    </td>
                                    <td>
                                        <form action="/mengelola-produk-admin/delete" method="post">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ $pr->code }}">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                    data-target="#modal-edit" data-id="{{ $pr->id }}" name="edit">Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" name="button"
                                                    onclick="confirmation($(this))">Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('add-modal')
    <form action="/mengelola-produk-admin/tambah" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="action" value="add">
        <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-header">
                                <h4>Gambar Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="gallery gallery-md">
                                    <div class="gallery-item">
                                        <input type="file" id="selectedFile" class="d-none" name="foto[]">
                                        <input type="button" class="btn gallery-item"
                                               data-image="{{ asset('assets/img/products/add.png') }}"
                                               onclick="document.getElementById('selectedFile').click();">
                                        <p class="text-center d-none" id="selectedText">Foto.jpg</p>
                                        @error('foto')
                                        <span style="font-size: 80%; color: #dc3545">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-header">
                                <h4>Detail Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                               class="form-control @error('nama_produk') is-invalid @enderror"
                                               name="nama_produk" id="nama_produk"
                                               placeholder="Ex : Kopi Gambung Asli" value="{{ old('nama_produk') }}">
                                        @error('nama_produk')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea name="deskripsi" id="deskripsi"
                                                  class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                                                  cols="78"
                                                  style="height:200px;">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Toko</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 @error('toko') is-invalid @enderror"
                                                name="toko" id="toko">
                                            <option value="">Pilih Toko</option>
                                            @foreach($stores as $store)
                                                <option
                                                    value="{{ $store->code }}" {{ old('toko') == $store->code ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 @error('kategori') is-invalid @enderror"
                                                name="kategori" id="kategori">
                                            <option value="">Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{ $category->code }}" {{ old('kategori') == $category->code ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('kategori')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Berat (gram)</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control @error('berat') is-invalid @enderror"
                                               name="berat" id="berat"
                                               placeholder="Ex : 0.10" value="{{ old('berat') }}">
                                        @error('berat')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <label class="col-sm-2 col-form-label">Harga</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                               name="harga" id="harga"
                                               placeholder="Ex : 20000" value="{{ old('harga') }}">
                                        @error('harga')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Dimensi (cm)</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control @error('length') is-invalid @enderror"
                                               name="length" id="length"
                                               placeholder="Panjang" value="{{ old('length') }}">
                                        @error('length')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <label class="col-sm-1 col-form-label text-center">X</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control @error('width') is-invalid @enderror"
                                               name="width" id="width"
                                               placeholder="Lebar" value="{{ old('width') }}">
                                        @error('width')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                    <label class="col-sm-1 col-form-label text-center">X</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control @error('height') is-invalid @enderror"
                                               name="height" id="height"
                                               placeholder="Tinggi" value="{{ old('height') }}">
                                        @error('height')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Total Stok (pcs)</label>
                                    <div class="col-sm-4">
                                        <input type="text"
                                               class="form-control @error('total_stock') is-invalid @enderror"
                                               name="total_stock" id="total_stock" value="{{ old('total_stock') }}">
                                        @error('total_stock')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row color-element d-none">
                                    <label class="col-sm-2 col-form-label">Warna</label>
                                    <div class="col-sm-4">
                                        <input type="text"
                                               class="form-control @error('warna') is-invalid @enderror"
                                               id="warna" value="{{ old('warna') }}">
                                        @error('warna')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="size d-none">
                                    <hr>
                                    <div class="gambung-tables">
                                        <table class="table table-striped" id="size-table">
                                            <thead>
                                            <tr>
                                                <th>Ukuran</th>
                                                <th>Stok</th>
                                                <th>Hapus</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr id="size-0">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.0') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.0') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.0')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.0') }}"
                                                           class="form-control @error('stock_size.0') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.0')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-1" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.1') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.1') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.1')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.1') }}"
                                                           class="form-control @error('stock_size.1') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.1')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-2" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.2') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.2') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.2')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.2') }}"
                                                           class="form-control @error('stock_size.2') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.2')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-3" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.3') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.3') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.3')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.3') }}"
                                                           class="form-control @error('stock_size.3') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.3')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 3px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-4" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.4') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.4') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.4')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.4') }}"
                                                           class="form-control @error('stock_size.4') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.4')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-5" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.5') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.5') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.5')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.5') }}"
                                                           class="form-control @error('stock_size.5') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.5')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-6" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.6') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.6') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.6')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.6') }}"
                                                           class="form-control @error('stock_size.6') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.6')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-7" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.7') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.7') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.7')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.7') }}"
                                                           class="form-control @error('stock_size.7') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-tambah'))">
                                                    @error('stock_size.7')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-tambah'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="add-size">
                                                <td class="text-left"><a href="#" onclick="add_size('add')">+ Tambah</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" onclick="checkSize($('#modal-tambah'))">Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="/mengelola-produk-admin/add-images" enctype="multipart/form-data"
          method="post" id="addImagesForm">
        @csrf
        <input type="file" id="addImages" class="d-none" name="foto[]">
        <input name="id" hidden>
    </form>

    <form action="/mengelola-produk-admin/edit" method="post">
        @csrf
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="code" id="code" value="{{ old('code') }}">
        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-header">
                                <h4>Gambar Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="gallery gallery-md">
                                    <img src="{{ asset('assets/img/products/add.png') }}" class="gallery-item"
                                         id="mainImage">

                                    <div class="gallery-item">
                                        <input type="button" class="btn gallery-item"
                                               data-image="{{ asset('assets/img/products/add.png') }}"
                                               onclick="$('#addImages').click();">
                                        <p class="text-center d-none" id="addImagesText">Foto.jpg</p>
                                    </div>

                                </div>
                            </div>
                            <div class="card-header">
                                <h4>Detail Produk</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                               class="form-control @error('nama_produk') is-invalid @enderror"
                                               name="nama_produk"
                                               placeholder="Ex : Kopi Gambung Asli" value="{{ old('nama_produk') }}">
                                        @error('nama_produk')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                            <textarea name="deskripsi"
                                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                                      rows="3" cols="78"
                                                      style="height:200px;">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Toko</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 @error('toko') is-invalid @enderror"
                                                name="toko" id="toko">
                                            <option disabled selected>Pilih Toko</option>
                                            @foreach($stores as $store)
                                                <option
                                                    value="{{ $store->code }}" {{ old('toko') ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Pilih Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 @error('kategori') is-invalid @enderror"
                                                name="kategori" id="kategori">
                                            <option disabled selected>Pilih Kategori</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{ $category->code }}" {{ old('kategori') ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('kategori')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Berat (gram)</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control @error('berat') is-invalid @enderror"
                                               name="berat" id="berat"
                                               placeholder="Ex : 0.10" value="{{ old('berat') }}">
                                        @error('berat')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <label class="col-sm-2 col-form-label">Harga</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                               name="harga" id="harga"
                                               placeholder="Ex : 20000" value="{{ old('harga') }}">
                                        @error('harga')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Dimensi (cm)</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control @error('length') is-invalid @enderror"
                                               name="length" id="length"
                                               placeholder="Panjang" value="{{ old('length') }}">
                                        @error('length')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <label class="col-sm-1 col-form-label text-center">X</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control @error('width') is-invalid @enderror"
                                               name="width" id="width"
                                               placeholder="Lebar" value="{{ old('width') }}">
                                        @error('width')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <label class="col-sm-1 col-form-label text-center">X</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control @error('height') is-invalid @enderror"
                                               name="height" id="height"
                                               placeholder="Tinggi" value="{{ old('height') }}">
                                        @error('height')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Total Stok (pcs)</label>
                                    <div class="col-sm-4">
                                        <input type="text"
                                               class="form-control @error('total_stock') is-invalid @enderror"
                                               name="total_stock" id="total_stock" value="{{ old('total_stock') }}">
                                        @error('total_stock')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row color-element d-none">
                                    <label class="col-sm-2 col-form-label">Warna</label>
                                    <div class="col-sm-4">
                                        <input type="text"
                                               class="form-control @error('warna') is-invalid @enderror"
                                               id="warna" value="{{ old('warna') }}">
                                        @error('warna')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="size d-none">
                                    <hr>
                                    <div class="gambung-tables">
                                        <table class="table table-striped" id="size-table">
                                            <thead>
                                            <tr>
                                                <th>Ukuran</th>
                                                <th>Stok</th>
                                                <th>Hapus</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr id="size-0">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.0') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.0') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.0')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.0') }}"
                                                           class="form-control @error('stock_size.0') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.0')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-1" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.1') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.1') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.1')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.1') }}"
                                                           class="form-control @error('stock_size.1') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.1')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-2" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.2') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.2') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.2')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.2') }}"
                                                           class="form-control @error('stock_size.2') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.2')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-3" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.3') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.3') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.3')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.3') }}"
                                                           class="form-control @error('stock_size.3') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.3')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 3px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-4" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.4') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.4') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.4')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.4') }}"
                                                           class="form-control @error('stock_size.4') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.4')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-5" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.5') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.5') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.5')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.5') }}"
                                                           class="form-control @error('stock_size.5') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.5')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-6" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.6') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.6') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.6')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.6') }}"
                                                           class="form-control @error('stock_size.6') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.6')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr id="size-7" class="d-none">
                                                <td>
                                                    <select
                                                        class="form-control @error('size.7') is-invalid @enderror">
                                                        <option value="" selected>Pilih Ukuran</option>
                                                        @foreach($sizes as $size)
                                                            <option
                                                                {{ (old('size.7') == $size->value) ? 'selected ' : '' }}
                                                                value="{{ $size->value }}">{{ $size->value }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size.7')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ old('stock_size.7') }}"
                                                           class="form-control @error('stock_size.7') is-invalid @enderror stock-size"
                                                           onkeyup="calculate_stock($('#modal-edit'))">
                                                    @error('stock_size.7')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-size" type="button"
                                                            onclick="delete_size($(this), $('#modal-edit'))"
                                                            style="height: 40px; width: 40px">
                                                        <i class="far fa-trash-alt fa-3x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="add-size">
                                                <td class="text-left"><a href="#" onclick="add_size('edit')">+
                                                        Tambah</a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('script')
    <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>

    <script type="text/javascript">
        @if ($errors->any())
        @if( $errors->has('warna') or (old('warna', null) != null))
        $('.color-element').removeClass('d-none');
        $('#warna').attr('name', 'warna');
        @endif

        @if($errors->has('size.0') or $errors->has('stock_size.0') or (old('size.0', null) != null) or (old('stock_size.0', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-0').find('select').attr('name', 'size[]');
        $('tr#size-0').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-0').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.1') or $errors->has('stock_size.1') or (old('size.1', null) != null) or (old('stock_size.1', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-1').find('select').attr('name', 'size[]');
        $('tr#size-1').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-1').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.2') or $errors->has('stock_size.2') or (old('size.2', null) != null) or (old('stock_size.2', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-2').find('select').attr('name', 'size[]');
        $('tr#size-2').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-2').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.3') or $errors->has('stock_size.3') or (old('size.3', null) != null) or (old('stock_size.3', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-3').find('select').attr('name', 'size[]');
        $('tr#size-3').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-3').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.4') or $errors->has('stock_size.4') or (old('size.4', null) != null) or (old('stock_size.4', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-4').find('select').attr('name', 'size[]');
        $('tr#size-4').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-4').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.5') or $errors->has('stock_size.5') or (old('size.5', null) != null) or (old('stock_size.5', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-5').find('select').attr('name', 'size[]');
        $('tr#size-5').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-5').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.6') or $errors->has('stock_size.6') or (old('size.6', null) != null) or (old('stock_size.6', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-6').find('select').attr('name', 'size[]');
        $('tr#size-6').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-6').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if($errors->has('size.7') or $errors->has('stock_size.7') or (old('size.7', null) != null) or (old('stock_size.7', null) != null))
        $('.size').removeClass('d-none');

        $('tr#size-7').find('select').attr('name', 'size[]');
        $('tr#size-7').find('.stock-size').attr('name', 'stock_size[]');
        $('tr#size-7').removeClass('d-none');
        $('input[name="total_stock"]').attr('readonly', true);
        @enderror

        @if(old('action') == 'add')
        $('#modal-tambah').modal('show');
        @else
        $('#modal-edit').modal('show');
        @endif

        @endif

        $("#selectedFile").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $('#selectedText').removeClass('d-none').text(fileName);
        });

        function reset() {
            resetModal();
            $('.color-element').addClass('d-none');
            $('#warna').removeAttr('name');

            $('.size').addClass('d-none');
            $('select[name="size[]"]').removeAttr('name');
            $('input[name="stock_size[]"]').removeAttr('name');
            $('input[name="total_stock"]').attr('readonly', false);
        }

        $("button[name='tambah']").click(function () {
            reset();
        });

        $("button[name='edit']").click(function () {
            reset();
            var id = $(this).data('id');
            $("input[name='id']").val(id);
            getEditData(id);
        });

        var xhr;
        $('#search').on('keyup', function () {
            if (xhr && xhr.readyState != 4) {
                xhr.abort();
            }

            xhr = $.ajax({
                type: 'GET',
                url: '{{ route('admin.produk.search') }}',
                data: {value: $(this).val()},
                dataType: 'json',
                success: function (data) {
                    let newRow = '';
                    $(data).each(function (i) {
                        newRow = newRow.concat('<tr>');
                        newRow = newRow.concat('<td>' + (i + 1) + '</td>');
                        newRow = newRow.concat('<td>' + data[i]['name'] + '</td>');
                        newRow = newRow.concat('<td><div class="badge badge-primary">' + data[i]['stock'] + '</div></td>');
                        newRow = newRow.concat('<td>' +
                            '<form action="{{ url('/mengelola-produk-admin/delete') }}" method="post">' +
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                            '<input type="hidden" name="id" value="' + data[i]['code'] + '">' +
                            '<button type="button" class="btn btn-outline-primary" data-toggle="modal"' +
                            'data-target="#modal-edit" data-id="' + data[i]['id'] + '" onclick="getEditData(' + data[i]['id'] + ')">Edit' +
                            '</button>' +
                            '<button type="button" class="btn btn-danger"' +
                            'name="button" onclick="confirmation($(this));">Delete' +
                            '</button>' +
                            '</form>' +
                            '</td>');
                        newRow = newRow.concat('</tr>');
                    });

                    $('#list tbody').empty();
                    $('#list tbody').append(newRow);
                }
            });
        });

        $('#modal-tambah select#kategori, #modal-edit select#kategori').on('change', function () {
            let kategori = $(this).val();
            $('.color-element').addClass('d-none');
            $('#warna').removeAttr('name');

            $('.size').addClass('d-none');
            $('select[name="size[]"]').removeAttr('name');
            $('input[name="stock_size[]"]').removeAttr('name');

            $('input[name="total_stock"]').attr('readonly', false);

            $.ajax({
                type: 'POST',
                url: '{{ route('category.status.admin') }}',
                data: {category: kategori},
                dataType: 'json',
                success: function (data) {
                    $(data).each(function (i) {
                        if (data[i].status.name == 'Warna') {
                            $('.color-element').removeClass('d-none');
                            $('#warna').attr('name', 'warna');
                        }

                        if (data[i].status.name == 'Ukuran') {
                            $('.size').removeClass('d-none');
                            $('tr#size-0').find('select').attr('name', 'size[]');
                            $('tr#size-0').find('.stock-size').attr('name', 'stock_size[]');
                            $('input[name="total_stock"]').attr('readonly', true);
                        }
                    });
                }
            });
        });

        function add_size(action) {
            let element;
            let i = 0;
            if (action == 'add') {
                element = $('#modal-tambah #size-table tbody');
                i = $('#modal-tambah #size-table tbody tr').not('.d-none').length;
            } else {
                element = $('#modal-edit #size-table tbody');
                i = $('#modal-edit #size-table tbody tr').not('.d-none').length;
            }

            element.find('tr#size-' + (i - 1)).removeClass('d-none');
            $('tr#size-' + (i - 1)).find('select').attr('name', 'size[]');
            $('tr#size-' + (i - 1)).find('.stock-size').attr('name', 'stock_size[]');
        }

        function fill_status(data) {
            if (data['product_detail'].length > 0) {
                $(data['product_detail']).each(function (i) {
                    $('#modal-edit #size-table tbody').find('tr#size-' + i).removeClass('d-none');
                    $('tr#size-' + i).find('select').attr('name', 'size[]');
                    $('tr#size-' + i).find('select').val(data['product_detail'][i].size);
                    $('tr#size-' + i).find('.stock-size').attr('name', 'stock_size[]');
                    $('tr#size-' + i).find('.stock-size').val(parseInt(data['product_detail'][i].stock));

                });

                $('.size').removeClass('d-none');
            }
        }

        function calculate_stock(modal) {
            let total_stock = 0;

            $('#' + modal.attr('id') + ' #size-table tbody tr').not($('.add-size')).each(function () {
                let stock = ($(this).find('.stock-size').val() == "") ? "0" : $(this).find('.stock-size').val();
                total_stock += parseInt(stock);
            });

            $('input[name="total_stock"]').val(total_stock);
        }

        function delete_size(element, modal) {
            let idToDelete = element.closest('tr').attr('id').substr(5);
            let elementStock = element.parent().parent().find('.stock-size').val();

            let total_stock = parseInt($('input[name="total_stock"]').val() == "" ? "0" : "") - parseInt(elementStock == "" ? "0" : "");
            $('input[name="total_stock"]').val(total_stock);
            $('#' + modal.attr('id') + ' table tbody tr[id*="size-"]').each(function (i) {
                if (i > idToDelete) {
                    let nextSize = $('#' + modal.attr('id') + ' table tbody tr#size-' + i).find('select').val();
                    let nextStock = $('#' + modal.attr('id') + ' table tbody tr#size-' + i).find('.stock-size').val();
                    $('#' + modal.attr('id') + ' table tbody tr#size-' + (i - 1)).find('select').val(nextSize);
                    $('#' + modal.attr('id') + ' table tbody tr#size-' + (i - 1)).find('.stock-size').val(nextStock);
                }
            });

            $('#' + modal.attr('id') + ' table tbody tr[id*="size-"]').not('.d-none').each(function (i) {
                if (i !== 0) {
                    if ($(this).find('.stock-size').val() == "" && $(this).find('select').val() == "") {
                        $(this).addClass('d-none');
                        $(this).find('select').removeAttr('name');
                        $(this).find('.stock-size').removeAttr('name');
                    }
                }
            });
        }

        function getEditData(id) {
            $.ajax({
                url: "mengelola-produk-admin/get-edit-data",
                method: "POST",
                data: {
                    id: id,
                },
                success: function (result) {
                    $('.deleteClass').remove();
                    if (result['images'].length > 0) {
                        $('#mainImage').css('display', 'block');
                        $("#mainImage").attr('src', ("assets/img/products" + result['images'][0]['image_name']));
                        for (var i = 0; i < result['images'].length; i++) {
                            if (result['images'][i]['main_image'] == 'OPTNO') {
                                $("#mainImage").after('<form action="/mengelola-produk-admin/delete-images" method="post" class="deleteClass">@csrf<button name="id" type="submit" value="' + result['images'][i]['id'] + '" class="btn gallery-item"><img src="assets/img/products/' + result['images'][i]['image_name'] + '" width="100%" height="100%"></button></form>');
                            }
                        }
                    }

                    $("input[name='nama_produk']").val(result['name']);
                    $("textarea[name='deskripsi']").val(result['description']);
                    $("select[name='toko']").val(result['store_code']).change();
                    $("select[name='kategori']").val(result['main_category']['code']).change();
                    $("input[name='berat']").val(result['weight'] * 100 / 100);
                    $("input[name='total_stock']").val(result['stock'] * 100 / 100);
                    $("input[name='harga']").val(result['price'] * 100 / 100);
                    $("input[name='width']").val(result['width'] * 100 / 100);
                    $("input[name='length']").val(result['length'] * 100 / 100);
                    $("input[name='height']").val(result['height'] * 100 / 100);
                    $("input[name='code']").val(result['code']);
                    fill_status(result);

                    setTimeout(
                        function () {
                            $('.color-element').find('input[name="warna"]').val(result['color']);
                        }, 2000);
                }
            })
        }

        function checkSize(element) {
            $('tr[id*="size-"]').not('.d-none').each(function (i) {
                if (i !== 0) {
                    if ($(this).find('.stock-size').val() == "" && $(this).find('select').val() == "") {
                        $(this).addClass('d-none');
                        $(this).find('select').removeAttr('name');
                        $(this).find('.stock-size').removeAttr('name');
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        $("#addImages").on("change", function () {
            $(this).closest('form').submit();
        });
    </script>

@endpush
