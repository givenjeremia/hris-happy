<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - HRIS SISTEM</title>

    <link rel="shortcut icon" href="{{ asset('assets/icon/resource.png') }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets_lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets_lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets_lte/dist/css/adminlte.min.css') }}">

    <!-- Custom Styles -->
    <style>
        body {
            background: linear-gradient(135deg, #2E8BFF, #1E3A8A);
            font-family: 'Source Sans Pro', sans-serif;
            color: black; /* Mengubah warna teks menjadi hitam */
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-primary {
            border-top: 3px solid #4E73DF;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #4E73DF;
            border-color: #4E73DF;
            transition: background-color 0.3s ease;
            border-radius: 25px;
        }

        .btn-primary:hover {
            background-color: #2E8BFF;
            border-color: #2E8BFF;
        }

        .login-box-msg {
            font-weight: bold;
            font-size: 20px;
            color: black;
            text-align: center;
            margin-bottom: 20px;
        }

        input::placeholder {
            color: #333;
        }

        .input-group-text {
            background: #4E73DF;
            color: black;
        }

        .input-group-text i {
            font-size: 1.2em;
        }

        .login-footer {
            margin-top: 10px;
            text-align: center;
            font-size: 14px;
            color: black;
        }

        .login-footer a {
            color: #4E73DF;
            text-decoration: none;
        }

        .login-footer a:hover {
            color: #2E8BFF;
        }

    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg">Silakan Masuk untuk Akses HRIS Sistem</p>

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
                    <button type="submit" id="btnLogin" class="btn btn-primary btn-block">Masuk</button>
                </form>
                <div class="login-footer">
                    <a href="#">Lupa Password?</a>
                </div>
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
                title: 'Sedang Masuk...',
                html: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                background: '#222',
                color: '#fff',
                padding: '1em',
                width: '400px',        
                heightAuto: false,      
                borderRadius: '8px',
                position: 'top-end'
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
                            icon: 'success',
                            title: 'Email dan Password Benar!',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#4CAF50',
                            color: '#fff',
                            borderRadius: '8px',
                            padding: '1em',
                            width: '400px',     
                            heightAuto: false,   
                            position: 'top-end'
                        }).then(() => {
                            location.href = "{{ route('home') }}";
                        });
                    } else {
                        Swal.fire({
                            title: 'Email atau Password Salah!',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2000,
                            background: '#FF3B30',
                            color: '#fff',
                            borderRadius: '8px',
                            padding: '1em',
                            width: '400px',
                            heightAuto: false,
                            position: 'top-end'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan pada Server, coba lagi!',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#FF3B30',
                        color: '#fff',
                        borderRadius: '8px',
                        padding: '1em',
                        width: '300px',
                        heightAuto: false,
                        position: 'top-end'
                    });
                }
            });
        });
    </script>
</body>

</html>
