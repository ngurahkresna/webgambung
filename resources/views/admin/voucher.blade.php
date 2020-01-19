@extends('layouts.dashboard-layout')

@section('page', 'Mengelola Voucher')

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
                        <form>
                            <div class="input-group">
                                <button type="button" class="btn btn-primary btn-tambah" data-toggle="modal"
                                        data-target="#modal-tambah" onclick="resetModal()"><i class="fas fa-plus"></i>
                                    Tambah Voucher
                                </button>
                                <input type="text" class="form-control" id="search" placeholder="Search">
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
                                <th>Kode Voucher</th>
                                <th>Jenis Voucher</th>
                                <th>Nominal</th>
                                <th>Masa Berlaku</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($voucher as $vc)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $vc->code }}</td>
                                    <td>{{ $vc->type=="VCRCB" ? 'Cashback':'Discount' }}</td>
                                    <td>{{ ($vc->percentage)*100/100 }}%</td>
                                    <td>{{ $vc->start_date }} - {{ $vc->end_date }}</td>
                                    <td>
                                        <form action="{{ url('/mengelola-voucher/delete') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="code" value="{{ $vc->code }}">
                                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                                    data-target="#modal-edit" data-id="{{ $vc->id }}" name="edit">Edit
                                            </button>
                                            <button type="button" class="btn btn-danger"
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
    <form action="/mengelola-voucher/tambah" method="post">
        @csrf
        <input type="hidden" name="action" value="add">
        <div class="modal fade" id="modal-tambah" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Voucher</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-9 col-9">
                                        <label>Kode Voucher*</label>
                                        <input name="kode" type="text"
                                               class="form-control @error('kode') is-invalid @enderror"
                                               placeholder="GX723H" value="{{ old('kode') }}">
                                        @error('kode')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3" style="margin-top:30px;">
                                        <button type="button" name="generate_token" class="btn btn-outline-primary">
                                            Generate Token
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Untuk Toko</label>
                                        <select name="toko[]"
                                                class="form-control select2 select2-multiple @error('toko') is-invalid @enderror"
                                                multiple>
                                            <option value="all" {{ old('toko') == 'all' ? 'selected' : '' }}>Semua
                                                Toko
                                            </option>
                                            @foreach($toko as $tk)
                                                <option
                                                    value="{{ $tk->code }}" {{ ( is_array(old('toko')) && in_array($tk->code, old('toko')) ) ? 'selected ' : '' }}>{{ $tk->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('toko')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Jenis Promo*</label>
                                        <select name="jenis"
                                                class="form-control select2 @error('jenis') is-invalid @enderror">
                                            <option value="VCRDS" {{ old('jenis') ? 'selected' : '' }}>Diskon</option>
                                            <option value="VCRCB" {{ old('jenis') ? 'selected' : '' }}>Cashback</option>
                                        </select>
                                        @error('jenis')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-3">
                                        <label>Nominal* %</label>
                                        <input name="nominal" type="text"
                                               class="form-control @error('nominal') is-invalid @enderror"
                                               value="{{ old('nominal') }}" placeholder="50">
                                        @error('nominal')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3">
                                        <label>Maksimal Diskon (Rp)</label>
                                        <input name="maksimal" type="text"
                                               class="form-control @error('maksimal') is-invalid @enderror"
                                               value="{{ old('maksimal') }}" placeholder="10000">
                                        @error('maksimal')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3">
                                        <label>Mulai Dari*</label>
                                        <input name="mulai" type="text" value="{{ old('mulai') }}"
                                               class="form-control datepicker @error('mulai') is-invalid @enderror">
                                        @error('mulai')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3">
                                        <label>Berakhir Pada*</label>
                                        <input name="berakhir" type="text" value="{{ old('berakhir') }}"
                                               class="form-control datepicker @error('berakhir') is-invalid @enderror">
                                        @error('berakhir')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Syarat dan Ketentuan</label>
                                        <textarea name="syarat"
                                                  class="form-control @error('syarat') is-invalid @enderror"
                                                  style="height:100px;">{{ old('syarat') }}</textarea>
                                        @error('syarat')
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

    <form action="/mengelola-voucher/edit" method="post">
        @csrf
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="code">
        <div class="modal fade" id="modal-edit" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Voucher</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-modal">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Kode Voucher*</label>
                                        <input name="kode" type="text"
                                               class="form-control @error('kode') is-invalid @enderror"
                                               placeholder="GX723H" value="{{ old('kode') }}" readonly>
                                        @error('kode')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Untuk Toko</label>
                                        <select name="toko[]"
                                                class="form-control select2 select2-multiple @error('toko') is-invalid @enderror"
                                                multiple>
                                            <option value="all" {{ old('toko') == 'all' ? 'selected' : '' }}>Semua
                                                Toko
                                            </option>
                                            @foreach($toko as $tk)
                                                <option
                                                    value="{{ $tk->code }}" {{ ( is_array(old('toko')) && in_array($tk->code, old('toko')) ) ? 'selected ' : '' }}>{{ $tk->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('toko')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Jenis Promo*</label>
                                        <select name="jenis"
                                                class="form-control select2 @error('jenis') is-invalid @enderror">
                                            <option value="VCRDS" {{ old('jenis') ? 'selected' : '' }}>Diskon</option>
                                            <option value="VCRCB" {{ old('jenis') ? 'selected' : '' }}>Cashback</option>
                                        </select>
                                        @error('jenis')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-3">
                                        <label>Nominal* %</label>
                                        <input name="nominal" type="text"
                                               class="form-control @error('nominal') is-invalid @enderror"
                                               value="{{ old('nominal') }}" placeholder="50">
                                        @error('nominal')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3">
                                        <label>Maksimal Diskon (Rp)</label>
                                        <input name="maksimal" type="text"
                                               class="form-control @error('maksimal') is-invalid @enderror"
                                               value="{{ old('maksimal') }}" placeholder="10000">
                                        @error('maksimal')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3">
                                        <label>Mulai Dari*</label>
                                        <input name="mulai" type="text" value="{{ old('mulai') }}"
                                               class="form-control datepicker @error('mulai') is-invalid @enderror">
                                        @error('mulai')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 col-3">
                                        <label>Berakhir Pada*</label>
                                        <input name="berakhir" type="text" value="{{ old('berakhir') }}"
                                               class="form-control datepicker @error('berakhir') is-invalid @enderror">
                                        @error('berakhir')
                                        <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Syarat dan Ketentuan</label>
                                        <textarea name="syarat"
                                                  class="form-control @error('syarat') is-invalid @enderror"
                                                  style="height:100px;">{{ old('syarat') }}</textarea>
                                        @error('syarat')
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
    <script src="{{ asset('node_modules/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>
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
                format: 'YYYY-MM-DD'
            }
        });

        $("button[name='edit']").click(function () {
            var id = $(this).data('id');
            getEditData(id);
        });

        let currVal = [];
        let prevVal = [];
        $('select[multiple]').on('change', function () {
            currVal = $(this).val();

            if (currVal.length < 1) {
                $('select[multiple] option[value="all"]').prop('selected', true).trigger('change.select2');
            } else {
                let isAll = $(currVal).not(prevVal).get();
                if (isAll.indexOf("all") > -1 && isAll.length == 1) {
                    $('select[multiple] option[value="all"]').prop('selected', true).trigger('change.select2');
                    $('select[multiple] option[value!="all"]').prop('selected', false).trigger('change.select2');
                } else
                    $('select[multiple] option[value="all"]').prop('selected', false).trigger('change.select2');
            }

            prevVal = currVal;
        });

        var xhr;
        $('#search').on('keyup', function () {
            if (xhr && xhr.readyState != 4) {
                xhr.abort();
            }

            xhr = $.ajax({
                type: 'GET',
                url: '{{ route('voucher.search') }}',
                data: {value: $(this).val()},
                dataType: 'json',
                success: function (data) {
                    let newRow = '';
                    $(data).each(function (i) {
                        let type = (data[i]['type'] == "VCRCB") ? "CashBack" : "Diskon";
                        newRow = newRow.concat('<tr>');
                        newRow = newRow.concat('<td>' + (i + 1) + '</td>');
                        newRow = newRow.concat('<td>' + data[i]['code'] + '</td>');
                        newRow = newRow.concat('<td>' + type + '</td>');
                        newRow = newRow.concat('<td>' + data[i]['percentage'] + '</td>');
                        newRow = newRow.concat('<td>' + data[i]['start_date'] + ' s.d ' + data[i]['end_date'] + '</td>');
                        newRow = newRow.concat('<td>' +
                            '<form action="{{ url('/mengelola-voucher/delete') }}" method="post">' +
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
            resetModal();
            $.ajax({
                url: "mengelola-voucher/get-edit-data",
                method: "POST",
                data: {
                    id: id,
                },
                success: function (result) {
                    $("input[name='kode']").val(result['code']);
                    $("select[name='jenis']").val(result['type']).change();
                    $("input[name='nominal']").val(result['percentage'] * 100 / 100);
                    $("input[name='maksimal']").val(result['max_price'] * 100 / 100);
                    $("input[name='mulai']").val(result['start_date']);
                    $("input[name='berakhir']").val(result['end_date']);
                    $("textarea[name='syarat']").val(result['terms']);
                    $("input[name='code']").val(result['code']);

                    if (result['term'].length > 0) {
                        if(result['term'].length == $('#modal-edit').find('select[multiple] option').length - 1) {
                            $('#modal-edit').find('select[multiple] option[value="all"]').prop('selected', true).trigger('change.select2');
                        } else {
                            $(result['term']).each(function (i) {
                                $('#modal-edit').find('select[multiple] option[value="all"]').prop('selected', false).trigger('change.select2').change();
                                $('#modal-edit').find('select[multiple] option[value="' + result['term'][i]['store_code'] + '"]').prop('selected', true).trigger('change.select2').change();
                            });
                        }
                    } else {
                        $('#modal-edit').find('select[multiple] option[value="all"]').prop('selected', true).trigger('change.select2');
                    }
                }
            })
        }
    </script>
    <script type="text/javascript">
        $("button[name='generate_token']").click(function () {
            var random = Math.random().toString(36).slice(2);
            random = random.substring(0, 6);
            $("input[name='kode']").val(random.toUpperCase());
        });
    </script>
@endpush
