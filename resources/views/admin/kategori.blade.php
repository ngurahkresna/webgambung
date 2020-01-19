@extends('layouts.dashboard-layout')

@section('page', 'Mengelola Kategori')

@push('css')
    <link rel="stylesheet" href="{{ asset('node_modules/prismjs/themes/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/daterangepicker/daterangepicker.css') }}">
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
                                    data-target="#modal-tambah" onclick="resetModal()"><i class="fas fa-plus"></i> Tambah Kategori
                            </button>
                            <input type="text" class="form-control" id="search" placeholder="Search">
                            <div class="input-group-btn">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
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
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($kategori as $ktg)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $ktg->name }}</td>
                                    <td>
                                        <form action="{{ url('/mengelola-kategori/delete') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ $ktg->code }}">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                    data-target="#modal-edit" data-id="{{ $ktg->id }}" name="edit">Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" value="{{ $ktg->id }}"
                                                    name="button" onclick="confirmation($(this))">Delete
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
    <form action="/mengelola-kategori/tambah" method="post">
        @csrf
        <input type="hidden" name="action" value="add">
        <div class="modal fade" id="modal-tambah" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nama Kategori</label>
                                        <input name="nama_kategori" type="text"
                                               class="form-control @error('nama_kategori') is-invalid @enderror"
                                               placeholder="Makanan" value="{{ old('nama_kategori') }}">
                                        @error('nama_kategori')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label class="form-label">Status</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="status[]" value="STS03"
                                                       class="selectgroup-input" {{ ( is_array(old('status')) && in_array('STS03', old('status')) ) ? 'checked ' : '' }}>
                                                <span class="selectgroup-button">Warna</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="status[]" value="STS01"
                                                       class="selectgroup-input" {{ ( is_array(old('status')) && in_array('STS01', old('status')) ) ? 'checked ' : '' }}>
                                                <span class="selectgroup-button">Ukuran</span>
                                            </label>
                                        </div>
                                        @error('status')
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

    <form action="/mengelola-kategori/edit" method="post">
        @csrf
        <input type="hidden" name="code">
        <input type="hidden" name="action" value="edit">
        <div class="modal fade" id="modal-edit" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Nama Kategori</label>
                                        <input name="nama_kategori" type="text"
                                               class="form-control @error('nama_kategori') is-invalid @enderror"
                                               placeholder="Makanan" value="{{ old('nama_kategori') }}">
                                        @error('nama_kategori')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label class="form-label">Status</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="status[]" value="STS03"
                                                       class="selectgroup-input" {{ ( is_array(old('status')) && in_array('STS03', old('status')) ) ? 'checked ' : '' }}>
                                                <span class="selectgroup-button">Warna</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="status[]" value="STS01"
                                                       class="selectgroup-input" {{ ( is_array(old('status')) && in_array('STS01', old('status')) ) ? 'checked ' : '' }}>
                                                <span class="selectgroup-button">Ukuran</span>
                                            </label>
                                        </div>
                                        @error('status')
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
                        <button type="submit" class="btn btn-primary" name="button">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ asset('node_modules/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
    <script>
        $('#level_kategori').change(function () {
            if ($('#level_kategori').val() == "sub") {
                $('#sub-kategori').removeAttr('hidden');
            } else {
                $('#sub-kategori').attr('hidden', 'true');
            }
        });
    </script>

    <script type="text/javascript">
        @if ($errors->any())
        @if(old('action') == 'add')
        $('#modal-tambah').modal('show');
        @else
        $('#modal-edit').modal('show');
        @endif
        @endif

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
                url: '{{ route('kategori.search') }}',
                data: {value: $(this).val()},
                dataType: 'json',
                success: function (data) {
                    let newRow = '';
                    $(data).each(function (i) {
                        newRow = newRow.concat('<tr>');
                        newRow = newRow.concat('<td>' + (i + 1) + '</td>');
                        newRow = newRow.concat('<td>' + data[i]['name'] + '</td>');
                        newRow = newRow.concat('<td>' +
                            '<form action="{{ url('/mengelola-kategori/delete') }}" method="post">' +
                            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                            '<input type="hidden" name="code" value="' + data[i]['code'] + '">' +
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
            resetModal()
            $.ajax({
                url: "mengelola-kategori/get-edit-data",
                method: "POST",
                data: {
                    id: id,
                },
                success: function (result) {
                    $("input[name='nama_kategori']").val(result['name']);
                    $("input[name='code']").val(result['code']);

                    $.each(result.status, function (i) {
                        $("input[value='" + result.status[i].status_code + "']").attr('checked', true);
                    });
                }
            })
        }
    </script>
@endpush
