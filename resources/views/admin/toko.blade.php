@extends('layouts.dashboard-layout')

@section('page', 'Mengelola Toko')

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
                        <div class="input-group">
                            <button type="button" class="btn btn-primary btn-tambah" data-toggle="modal"
                                    data-target="#modal-tambah" onclick="resetModal();"><i class="fas fa-plus"></i>
                                Tambah Toko
                            </button>
                            <input type="text" class="form-control" id="search" placeholder="Search">
                            <div class="input-group-btn">
                                <button class="btn btn-primary" id="btnSearch"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive gambung-tables">
                        <table class="table table-striped" id="list">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Toko</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($toko as $tk)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $tk->name }}</td>
                                    <td>
                                        <form action="{{ url('/mengelola-toko/delete') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $tk->code }}">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                    data-target="#modal-edit" data-id="{{ $tk->id }}" name="edit">Edit
                                            </button>
                                            <button type="button" class="btn btn-danger"
                                                    name="button" onclick="confirmation($(this));">Delete
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
    <form action="/mengelola-toko/tambah" method="post">
        @csrf
        <input type="hidden" name="action" value="add">
        <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Toko</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4 col-4">
                                        <img src="{{ asset('assets/img/gambung_coklat.png') }}" alt="Foto Profile"
                                             height="100px" width="200px">
                                    </div>
                                    <div class="form-group col-md-8 col-8">
                                        <label>Nama Pemilik UKM</label>
                                        <select
                                            class="form-control select2 @error('username') is-invalid @enderror"
                                            name="username">
                                            <option disabled selected>Pilih Pemilik UKM</option>
                                            @foreach($penjual as $pen)
                                                <option
                                                    value="{{ $pen->username }}" {{ old('username') ? 'selected' : '' }}>{{ $pen->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('username')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nama Toko</label>
                                        <input type="text" name="nomor_toko"
                                               class="form-control @error('nomor_toko') is-invalid @enderror"
                                               placeholder="Gambung Store" value="{{ old('nomor_toko') }}">
                                        @error('nomor_toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Deskripsi Toko</label>
                                        <textarea
                                            class="form-control @error('deskripsi_toko') is-invalid @enderror"
                                            name="deskripsi_toko" style="height:150px;"
                                            placeholder="Deskripsikan toko anda disini...">{{ old('deskripsi_toko') }}</textarea>
                                        @error('deskripsi_toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Alamat Toko</label>
                                        <textarea
                                            class="form-control @error('alamat_toko') is-invalid @enderror"
                                            name="alamat_toko" style="height:150px;"
                                            placeholder="Jl Raya Gambung">{{ old('alamat_toko') }}</textarea>
                                        @error('alamat_toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nomor Telephone</label>
                                        <input type="text" name="nomor_telephone"
                                               class="form-control @error('nomor_telephone') is-invalid @enderror"
                                               placeholder="0846537123" value="{{ old('nomor_telephone') }}">
                                        @error('nomor_telephone')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label class="form-label">Jasa Ekspedisi</label>
                                        <div class="selectgroup selectgroup-pills">
                                            @foreach($expeditions as $expedition)
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="ekspedisi[]"
                                                           value="{{ $expedition->code }}"
                                                           {{ ( is_array(old('ekspedisi')) && in_array($expedition->code, old('ekspedisi')) ) ? 'checked ' : '' }}
                                                           class="selectgroup-input" {{ old('ekspedisi') ? 'selected' : '' }}>
                                                    <span class="selectgroup-button">{{ $expedition->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('ekspedisi')
                                        <span style="font-size: 80%; color: #dc3545;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="/mengelola-toko/edit" method="post">
        @csrf
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id">
        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Toko</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4 col-4">
                                        <img src="{{ asset('assets/img/gambung_coklat.png') }}" alt="Foto Profile"
                                             height="100px" width="200px">
                                    </div>
                                    <div class="form-group col-md-8 col-8">
                                        <label>Nama Pemilik UKM</label>
                                        <input type="text" name="nama_pemilik_ukm"
                                               class="form-control @error('nama_pemilik_ukm') is-invalid @enderror"
                                               value="{{ old('nama_pemilik_ukm') }}" readonly>
                                        @error('nama_pemilik_ukm')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nama Toko</label>
                                        <input type="text" name="nama_toko"
                                               class="form-control @error('nama_toko') is-invalid @enderror"
                                               placeholder="Gambung Store" value="{{ old('nama_toko') }}">
                                        @error('nama_toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Deskripsi Toko</label>
                                        <textarea class="form-control @error('deskripsi_toko') is-invalid @enderror"
                                                  name="deskripsi_toko" style="height:150px;"
                                                  placeholder="Deskripsikan toko anda disini...">{{ old('deskripsi_toko') }}</textarea>
                                        @error('deskripsi_toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Alamat Toko</label>
                                        <textarea class="form-control @error('alamat_toko') is-invalid @enderror"
                                                  name="alamat_toko" style="height:150px;"
                                                  placeholder="Jl Raya Gambung">{{ old('alamat_toko') }}</textarea>
                                        @error('alamat_toko')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nomor Telephone</label>
                                        <input type="text" name="nomor_telephone"
                                               class="form-control @error('nomor_telephone') is-invalid @enderror"
                                               placeholder="0846537123" value="{{ old('nomor_telephone') }}">
                                        @error('nomor_telephone')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="button">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
    <script src="{{ asset('node_modules/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        @if ($errors->any())
        @if(old('action') == 'add')
        $('#modal-tambah').modal('show');
        @else
        $('#modal-edit').modal('show');
        @endif
        @endif

        $(".datepicker").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        $("button[name='edit']").click(function () {
            var id = $(this).data('id');
            getEditData(id);
        });

        var xhr;
        $('#search').on('keyup', function () {
            if (xhr && xhr.readyState != 4) {
                xhr.abort();
            }

            xhr = $.ajax({
                type: 'GET',
                url: '{{ route('toko.search') }}',
                data: {value: $(this).val()},
                dataType: 'json',
                success: function (data) {
                    let newRow = '';
                    $(data).each(function (i) {
                        newRow = newRow.concat('<tr>');
                        newRow = newRow.concat('<td>' + (i + 1) + '</td>');
                        newRow = newRow.concat('<td>' + data[i]['name'] + '</td>');
                        newRow = newRow.concat('<td>' +
                            '<form action="{{ url('/mengelola-toko/delete') }}" method="post">' +
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

        function getEditData(id) {
            resetModal();

            $.ajax({
                url: "mengelola-toko/get-edit-data",
                method: "POST",
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (result) {
                    $("input[name='id']").val(result['id']);
                    $("input[name='nama_pemilik_ukm']").val(result['users']['name']);
                    $("input[name='nama_toko']").val(result['name']);
                    $("textarea[name='deskripsi_toko']").val(result['description']);
                    $("textarea[name='alamat_toko']").val(result['address_1']);
                    $("input[name='nomor_telephone']").val(result['phone_1']);
                    $("button[name='button']").val(result['id']);
                }
            });
        }
    </script>
@endpush
