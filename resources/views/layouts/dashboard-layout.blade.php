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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="{{ asset('node_modules/selectric/public/selectric.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

@stack('css')

<!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">


</head>

<body>
<div id="app">
    <div class="main-wrapper">
        <div class="navbar-bg"></div>
    @include('layouts.navbar-dashboard')

    @include('layouts.sidebar-dashboard')

    <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <h1>@yield('page')</h1>
                </div>
                @yield('content')
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; 2019
                <div class="bullet"></div>
                Gambung Store</a>
            </div>
        </footer>
    </div>
</div>

@yield('add-modal')

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
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        getNotification();

        function getNotification(){
          $.ajax({
            url: "/get-notification-dashboard",
            method: "GET",
            success: function(result){
              console.log(result);
              if (result.message_new == 'new') {
                $('.message-toggle').addClass('beep');
              }
              if (result.notif_new == 'new') {
                $('.notification-toggle').addClass('beep');
              }
              $('#pesan .dropdown-list-content').append(result.messages);
              $('#notif .dropdown-list-content').append(result.notifications);
            }
          })
        }

        $('.notif').on('click', function(){
          console.log("a");
          var data = $(this).data('notif');
          console.log(data);
          $.ajax({
            url: "/update-notification",
            method: "get",
            data:{data: data},
            success: function(result){
              console.log(result);
              if (data == "notification") {
                $('.notification-toggle').removeClass('beep');
              }
              if (data == "message") {
                $('.message-toggle').removeClass('beep');
              }
            }
          })
        });
    });

    function confirmation(element) {
        Swal.fire({
            title: "Anda Yakin?",
            type: "warning",
            showConfirmButton: true,
            showCancelButton: true,
            showCloseButton: false,
            allowEscapeKey: false,
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                element.parent('form').submit();
            } else {
                return false;
            }
        });
    }

    function resetModal() {
        resetForm($('#modal-tambah').closest('form'));
        resetForm($('#modal-edit').closest('form'));
        $('.invalid-feedback').remove();
        $('input, select, textarea').removeClass('is-invalid');
    }

    function resetForm($form) {
        $form.find('input:text, input:password, input:file, input[type="email"], select, textarea').val('');
        $form.find('select[multiple]').val('').change();
        $form.find('input:radio, input:checkbox')
            .removeAttr('checked').removeAttr('selected');
    }
</script>

@stack('script')

</body>
</html>
