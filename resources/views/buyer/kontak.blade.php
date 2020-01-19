  @extends('layouts.client-layout')

  @section('page', 'Kontak Kami')

  @section('content')
  <div class="container-fluid page" style="padding:100px 200px 10px 200px;">
    <div class="card text-center">
      <div class="card-body">
        <img src="{{ asset('assets/img/gambung_coklat.png') }}" alt="logo gambung" height="200" width="450">
        <h3 class="font-weight-bold my-2">Kunjungi Kami</h3>
        <p>Jl Raya Gambung RT 3 RW 8 Desa Mekarsari<br>Jam operasional pukul 09.00 - 17.00 | Minggu Libur</p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63348.78346519294!2d107.40805929731508!3d-7.091301712732746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e688b432de520d3%3A0x401e8f1fc28c530!2sCiwidey%2C+Bandung%2C+West+Java!5e0!3m2!1sen!2sid!4v1556550295703!5m2!1sen!2sid" width="90%" height="500" frameborder="0" style="border:0" allowfullscreen=""></iframe>
      </div>
    </div>
  </div>

  <div class="container-fluid page" style="padding:10px 200px 100px 200px;">
    <div class="card text-center">
      <div class="card-body">
        <h3 class="font-weight-bold my-2">Hubungi Kami</h3>
        <p>Dapatkan informasi lebih lebih lanjut, hubungi kami pada
          <br>Email : gambungstore@gmail.com | +62 85886982764 (Muhammad Ramdan) | +62 85795852874 (Syam Shahid)
        </p>
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

  </script>
  @endpush
