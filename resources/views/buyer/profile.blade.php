@extends('layouts.client-layout')

@section('page', 'Profile')

@section('content')
    <div class="container-fluid page" style="padding:100px 200px;">
        <div class="card" style="padding:10%;">
            <h2>Edit Profile</h2>
            <div class="card-body">
                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-5">
                        <div class="card border-0">
                            <form action="/profile/avatar" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="profile-photo">
                                    @if($data->avatar != null)
                                        <img alt="image" id="avatar-img"
                                             src="{{ asset('assets/img/avatar/' . $data->avatar ) }}"
                                             class="profile-widget-picture"
                                             width="100%">
                                    @else
                                        <img alt="image" id="avatar-img"
                                             src="../assets/img/avatar/avatar-1.png"
                                             class="profile-widget-picture"
                                             width="100%">
                                    @endif
                                    <div class="custom-file mt-3" style="z-index:0">
                                        <input type="file" class="custom-file-input" id="avatar" name="avatar">
                                        <label class="custom-file-label" for="customFile">Pilih Foto</label>
                                    </div>
                                    @error('avatar')
                                    <div style="font-size: 80%; color: #dc3545;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                    <div class="text-right mt-3">
                                        <button class="btn btn-success" type="submit">Upload Foto</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-7">
                        <div class="card border-0 pt-0">
                            <form method="post" class="needs-validation" novalidate="" action="/profile/edit"
                                  method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-12 col-12">
                                            <label>Username</label>
                                            <input type="text" class="form-control" value="{{ $data->username }}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 col-12">
                                            <label>Email</label>
                                            <input type="email" class="form-control" value="{{ $data->email }}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 col-12">
                                            <label>Nama</label>
                                            <input name="name" type="text" class="form-control"
                                                   value="{{ $data->name }}" required="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Kota</label>
                                                <select class="form-control" name="city">
                                                    @foreach($cities as $city)
                                                        <option
                                                            value="{{ $city['city_id'] }}" {{ $city['city_id'] == $data->city ? 'selected' : '' }} >{{ $city['city_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Tanggal Lahir</label>
                                                <input name="birthday" type="text" class="form-control datepicker"
                                                       value="{{ $data->birthday }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control" style="height:150px;"
                                                      required>{{ $data->address_1 }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 col-12">
                                            <label>Nomor Telephone</label>
                                            <input name="telfon" type="text" class="form-control"
                                                   value="{{ $data->phone }}" required="">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button class="btn btn-success" type="submit">Simpan Data</button>
                                    </div>
                            </form>
                            <hr>
                            <form class="" action="{{ url('/profile/ubah-password') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Password Lama</label>
                                        <input name="passwordlama" type="password"
                                               class="form-control @error('passwordlama') is-invalid @enderror"
                                               value="">
                                        @error('passwordlama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Password</label>
                                        <input name="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror" value="">
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Retype-Password</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password"
                                               class="form-control @error('password_confirmation') is-invalid @enderror"
                                               value="">
                                        @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-success" type="submit">Ubah Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('node_modules/daterangepicker/daterangepicker.js') }}"></script>
    <script>

        $(".datepicker").daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });

        $('#avatar').on('change', function () {
            let input = $(this)[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#avatar-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        });

    </script>
@endpush
