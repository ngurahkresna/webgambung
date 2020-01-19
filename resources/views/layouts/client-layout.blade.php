<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Gambung &mdash; @yield('page')</title>
  <link rel="icon" href="{{ asset('assets/img/gambung_coklat.png') }}">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="{{ asset('node_modules/selectric/public/selectric.css') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />

  @stack('css')

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/client-custom.css') }}">


</head>

<body>
  <div>
    @include('layouts.navbar-client')
    <!-- Main Content -->
    <div class="main-content">
      <section class="section">
        @yield('content')
      </section>
    </div>
    <footer class="main-footer">
      @include('layouts.footer-client')
    </footer>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  <script src="{{ asset('assets/js/global_variables.js') }}"></script>

  <!-- JS Libraies -->
  @include('sweetalert::alert')
  <script type="text/javascript" src="{{ asset('node_modules/selectric/public/jquery.selectric.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <!-- Page Specific JS File -->
  <script type="text/javascript">
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    getNotification();

    function getNotification(){
      $.ajax({
        url: "/get-notification",
        method: "GET",
        success: function(result){
          console.log(result);
          if (result.message_new == 'new') {
            $('#notif-message .badge').removeClass('d-none');
          }
          if (result.notif_new == 'new') {
            $('#notif-transaksi .badge').removeClass('d-none');
          }
          $('#notif-message .dropdown-menu').append(result.messages);
          $('#notif-transaksi .dropdown-menu').append(result.notifications);
        }
      })
    }

    $('.notif').on('click', function(){
      var data = $(this).data('notif');
      console.log(data);
      $.ajax({
        url: "/update-notification",
        method: "get",
        data:{data: data},
        success: function(result){
          console.log(result);
          if (data == "notification") {
            $('#notif-transaksi .badge').addClass('d-none');
          }
          if (data == "message") {
            $('#notif-message .badge').addClass('d-none');
          }
        }
      })
    });

  });
</script>

@stack('script')

</body>
</html>
