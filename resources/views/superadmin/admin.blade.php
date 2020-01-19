@extends('layouts.dashboard-layout')

@section('page', 'Manajemen Admin')

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
                                    data-target="#modal-tambah" onclick="resetModal()"><i class="fas fa-plus"></i>
                                Tambah
                                Admin
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
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($admin as $adm)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $adm->email }}</td>
                                    <td>
                                        <form action="{{ url('/manajemen-admin/delete') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $adm->id }}">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                    data-target="#modal-edit" data-id="{{ $adm->id }}" name="edit">Edit
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
    <form action="/manajemen-admin/tambah" method="post">
        @csrf
        <input type="hidden" name="action" value="add">
        <div class="modal fade" id="modal-tambah" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Admin</h5>
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
                                             height="100px" width="100%">
                                    </div>
                                    <div class="form-group col-md-4 col-4">
                                        <label>Nama Admin</label>
                                        <input type="text" name="nama_admin" value="{{ old('nama_admin') }}"
                                               class="form-control @error('nama_admin') is-invalid @enderror"
                                               placeholder="Nama Admin">
                                        @error('nama_admin')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-4">
                                        <label>Username</label>
                                        <input type="text" name="username" value="{{ old('username') }}"
                                               class="form-control @error('username') is-invalid @enderror"
                                               placeholder="Username">
                                        @error('username')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Email</label>
                                        <input name="email" type="email" value="{{ old('email') }}"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="admin@gmail.com">
                                        @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Kota</label>
                                            <select class="form-control select2 @error('kota') is-invalid @enderror"
                                                    name="kota">
                                                <option disabled selected>Pilih Kota</option>
                                                @foreach($cities as $city)
                                                    <option
                                                        value="{{ $city['city_name'] }}" {{ old('kota') == $city['city_name'] ? 'selected' : '' }}>{{ $city['city_name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('kota')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="text" value="{{ old('tanggal_lahir') }}"
                                                   class="form-control datepicker @error('tanggal_lahir') is-invalid @enderror"
                                                   name="tanggal_lahir">
                                            @error('tanggal_lahir')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Alamat</label>
                                        <textarea name="alamat"
                                                  class="form-control @error('alamat') is-invalid @enderror"
                                                  style="height:150px;"
                                                  placeholder="Masukan alamat anda">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nomor Telephone</label>
                                        <input name="nomor_telephone" type="text" value="{{ old('nomor_telephone') }}"
                                               class="form-control @error('nomor_telephone') is-invalid @enderror"
                                               placeholder="085 000 000 000">
                                        @error('nomor_telephone')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Password</label>
                                        <input name="password" type="password" value="{{ old('password') }}"
                                               class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Retype-Password</label>
                                        <input name="password_confirmation" type="password"
                                               value="{{ old('password_confirmation') }}"
                                               class="form-control @error('password_confirmation') is-invalid @enderror">
                                        @error('password_confirmation')
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
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="/manajemen-admin/edit" method="post">
        @csrf
        <input type="hidden" name="action" value="edit">
        <div class="modal fade" id="modal-edit" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Admin</h5>
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
                                             height="100px" width="100%">
                                    </div>
                                    <div class="form-group col-md-4 col-4">
                                        <label>Nama Admin</label>
                                        <input type="text" name="nama_admin" value="{{ old('nama_admin') }}"
                                               class="form-control @error('nama_admin') is-invalid @enderror">
                                        @error('nama_admin')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 col-4">
                                        <label>Username</label>
                                        <input type="text" name="username" value="{{ old('username') }}"
                                               class="form-control @error('username') is-invalid @enderror" readonly>
                                        @error('username')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Email</label>
                                        <input name="email" type="email" value="{{ old('email') }}"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="admin@gmail.com" readonly>
                                        @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Kota</label>
                                            <select class="form-control select2 @error('kota') is-invalid @enderror"
                                                    name="kota">
                                                <option disabled selected>Pilih Kota</option>
                                                @foreach($cities as $city)
                                                    <option
                                                        value="{{ $city['city_name'] }}" {{ old('kota') == $city['city_name'] ? 'selected' : '' }}>{{ $city['city_name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('kota')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="text" value="{{ old('tanggal_lahir') }}"
                                                   class="form-control datepicker @error('tanggal_lahir') is-invalid @enderror"
                                                   name="tanggal_lahir">
                                            @error('tanggal_lahir')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Alamat</label>
                                        <textarea name="alamat"
                                                  class="form-control @error('alamat') is-invalid @enderror"
                                                  style="height:150px;">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nomor Telephone</label>
                                        <input name="nomor_telephone" type="text" value="{{ old('nomor_telephone') }}"
                                               class="form-control @error('nomor_telephone') is-invalid @enderror">
                                        @error('nomor_telephone')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Password</label>
                                        <input name="password" type="password" value="{{ old('password') }}"
                                               class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Retype-Password</label>
                                        <input name="password_confirmation" type="password"
                                               value="{{ old('password_confirmation') }}"
                                               class="form-control @error('password_confirmation') is-invalid @enderror">
                                        @error('password_confirmation')
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
    <script type="text/javascript">
        $(document).ready(function () {
            @if (count($errors) > 0)
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
                    format: 'YYYY-MM-DD'
                }
            });

            $("button[name='edit']").on('click', function () {
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
                    url: '{{ route('admin.search') }}',
                    data: {value: $(this).val()},
                    dataType: 'json',
                    success: function (data) {
                        let newRow = '';
                        $(data).each(function (i) {
                            newRow = newRow.concat('<tr>');
                            newRow = newRow.concat('<td>' + (i + 1) + '</td>');
                            newRow = newRow.concat('<td>' + data[i]['email'] + '</td>');
                            newRow = newRow.concat('<td>' +
                                '<form action="{{ url('/manajemen-admin/delete') }}" method="post">' +
                                '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                                '<input type="hidden" name="id" value="' + data[i]['id'] + '">' +
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
        });

        function getEditData(id) {
            resetModal();

            $.ajax({
                url: "manajemen-admin/get-edit-data",
                method: "POST",
                data: {
                    id: id,
                },
                success: function (result) {
                    $("input[name='nama_admin']").val(result[0]['name']);
                    $("input[name='username']").val(result[0]['username']);
                    $("select[name='kota']").val(result[0]['city']).change();
                    $("input[name='tanggal_lahir']").val(result[0]['birthday']);
                    $("textarea[name='alamat']").val(result[0]['address_1']);
                    $("input[name='nomor_telephone']").val(result[0]['phone']);
                    $("input[name='email']").val(result[0]['email']);
                    $("button[name='button']").val(result[0]['id']);
                }
            })
        }

    </script>
@endpush
