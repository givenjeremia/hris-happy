<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HRIS SISTEM</title>

    <link rel="shortcut icon" href="{{ asset('assets/icon/resource.png') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets_lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets_lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets_lte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            {{-- <div class="card-header text-center">
                <a href="/" class="">
                    <img src="#" alt="AdminLTE Logo"
                        class=" img-fluid img-circle elevation-3 w-50" style="opacity: .8">
                </a>
            </div> --}}
            <div class="card-body">
                <p class="login-box-msg">Silahkan Login Aplikasi HRIS!</p>

                <form id="loginForm" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="btnLogin" class="btn btn-primary btn-block">Login</button>
                </form>


                <!-- /.social-auth-links -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('assets_lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets_lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets_lte/dist/js/adminlte.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $('#btnLogin').on('click', function(e) {
            e.preventDefault();
            let act = '{{ route("login") }}'
            let form_data = new FormData(document.querySelector("#loginForm"));
            form_data.append('_token', '{{ csrf_token() }}')
            $.ajax({
                url: act,
                type: "POST",
                data: form_data,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status == "success") {
                        Swal.fire({
                            title: data.msg,
                            icon: 'success',
                            showConfirmButton: true
                        }).then(function(result) {
                            location.href = "{{ route('home') }}"
                        });

                    } else {
                        Swal.fire({
                            title: data.msg,
                            icon: 'error',
                            showConfirmButton: true
                        })
                    }
                }
            })
        })
    </script>

</body>

</html>
