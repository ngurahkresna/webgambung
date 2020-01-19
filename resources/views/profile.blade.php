@extends('layouts.dashboard-layout')

@section('page', 'Profile')

@section('content')

    <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card">
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
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="avatar">
                            <label class="custom-file-label" style="@error('avatar') border-color: #dc3545 @enderror"
                                   for="customFile">Pilih Foto</label>
                        </div>
                        @error('avatar')
                        <div style="font-size: 80%; color: #dc3545;">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                        <div class="text-right mt-3">
                            <button class="btn btn-primary" type="submit">Upload Foto</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <form method="post" action="/profile/edit" method="post">
                    @csrf
                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Username</label>
                                <input type="text" class="form-control" value="{{ $data->username }}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $data->email }}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Nama</label>
                                <input name="name" type="text"
                                       class="form-control" style="@error('name') border-color: #dc3545 @enderror"
                                       value="{{ old('name', $data->name) }}">
                                @error('name')
                                <span style="font-size: 80%; color: #dc3545;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <div class="form-group">
                                    <label>Kota</label>
                                    <select class="form-control select2"
                                            style="@error('city') border-color: #dc3545 @enderror"
                                            name="city">
                                        @foreach($cities as $city)
                                            <option
                                                value="{{ $city['city_id'] }}" {{ old('city', $data->city) == $city['city_id'] ? 'selected' : '' }} >{{ $city['city_name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                    <span style="font-size: 80%; color: #dc3545;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input name="birthday" type="text"
                                           class="form-control datepicker"
                                           style="@error('birthday') border-color: #dc3545 @enderror"
                                           value="{{ old('datepicker', $data->birthday) }}">
                                    @error('birthday')
                                    <span style="font-size: 80%; color: #dc3545;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Alamat</label>
                                <textarea name="alamat" class="form-control"
                                          style="height:150px; @error('alamat') border-color: #dc3545 @enderror">{{ old('alamat', $data->address_1) }}</textarea>
                                @error('alamat')
                                <span style="font-size: 80%; color: #dc3545;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Nomor Telephone</label>
                                <input name="telfon" type="text"
                                       class="form-control" style="@error('telfon') border-color: #dc3545 @enderror"
                                       value="{{ old('telfon', $data->phone) }}">
                                @error('telfon')
                                <span style="font-size: 80%; color: #dc3545;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
                <hr>
                <form method="post" action="{{ url('/profile/ubah-password') }}"
                      method="post">
                    @csrf
                    <div class="card-header">
                        <h4>Ubah Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label>Password Lama</label>
                                <input name="passwordlama" type="password"
                                       class="form-control @error('passwordlama') is-invalid @enderror" value="">
                                @error('passwordlama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input name="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" value="">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <div class="form-group">
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
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('node_modules/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

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
