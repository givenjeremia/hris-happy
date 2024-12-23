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
    <style>
        body {
            background: linear-gradient(to bottom right, #4e54c8, #8f94fb);
            color: white;
        }

        .login-box {
            margin-top: 10vh;
        }

        .card-primary {
            border-top: 3px solid #8f94fb;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #4e54c8;
            border-color: #4e54c8;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #8f94fb;
            border-color: #8f94fb;
        }

        .login-box-msg {
            font-weight: bold;
            font-size: 18px;
        }

        input::placeholder {
            color: #aaa;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg">Silahkan Login Aplikasi HRIS!</p>

                <form id="loginForm" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="btnLogin" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets_lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets_lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets_lte/dist/js/adminlte.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#btnLogin').on('click', function (e) {
            e.preventDefault();
            let act = '{{ route("login") }}';
            let form_data = new FormData(document.querySelector("#loginForm"));
            form_data.append('_token', '{{ csrf_token() }}');

            Swal.fire({
                title: 'Logging in...',
                html: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                background: 'rgba(0,0,0,0.9)',
                color: '#fff',
            });

            $.ajax({
                url: act,
                type: "POST",
                data: form_data,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status === "success") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: data.msg,
                            showConfirmButton: false,
                            timer: 1500,
                            background: 'rgba(0,0,0,0.9)',
                            color: '#fff',
                        }).then(() => {
                            location.href = "{{ route('home') }}";
                        });
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data.msg,
                            showConfirmButton: false,
                            timer: 2000,
                            background: 'rgba(0,0,0,0.9)',
                            color: '#fff',
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Server error, please try again!',
                        showConfirmButton: false,
                        timer: 2000,
                        background: 'rgba(0,0,0,0.9)',
                        color: '#fff',
                    });
                }
            });
        });
    </script>
</body>

</html>
