<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login Access HRIS Sistem</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />
</head>

<body style="
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #ffffff;
      font-size: 14px;
    ">
    <div style="
        max-width: 680px;
        margin: 0 auto;
        padding: 45px 30px 60px;
        background: #f4f7ff;
        background-repeat: no-repeat;
        background-size: 800px 452px;
        background-position: top center;
        font-size: 14px;
        color: #434343;
      ">
        <header>
            <table style="width: 100%;">
                <tbody>
                    <tr style="height: 0;">
                        <td>
                            {{-- <img alt=""
                                src="https://titik-koma.givenjeremia.com/brand/logo/titik_koma.png"
                                height="30px" /> --}}
                        </td>
                        <td style="text-align: right;">
                            <span style="font-size: 16px; line-height: 30px; color: black;">{{ $data['date'] }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>

        <main>
            <div style="
            margin: 0;
            margin-top: 70px;
            padding: 92px 30px 115px;
            background: #ffffff;
            border-radius: 30px;
            text-align: center;
          ">
                <div style="width: 100%; max-width: 489px; margin: 0 auto;">
                    <h1 style="
                margin: 0;
                font-size: 24px;
                font-weight: 500;
                color: #1f1f1f;
              ">
                        Akses Login Kamu
                    </h1>
                    <p style="
                            margin: 0;
                            margin-top: 17px;
                            font-size: 16px;
                            font-weight: 500;
                        ">
                        Hallo {{ $data['nama'] }},
                    </p>
                    <p style="
                            margin: 0;
                            margin-top: 17px;
                            font-weight: 500;
                            letter-spacing: 0.56px;
                        ">
                        Di Rekomendasikan Mengubah Password, Setelah Berhasil Login
                        <span style="font-weight: 600; color: #1f1f1f;">Jangan Sebarkan Password!</span>.
                        
                    </p>

                    <p style="
                            margin: 0;
                            margin-top: 17px;
                            font-weight: 500;
                            letter-spacing: 0.56px;
                        ">
                        Email : 
                        <span style="font-weight: 600; color: #1f1f1f;">{{ $data['email'] }}</span>
                        
                    </p>

                    <p style="
                            margin: 0;
                            margin-top: 17px;
                            font-weight: 500;
                            letter-spacing: 0.56px;
                        ">
                        Password : 
                        <span style="font-weight: 600; color: #1f1f1f;"> {{ $data['password'] }}</span>
                        
                    </p>

                   
                </div>
            </div>

        </main>

        <footer style="
          width: 100%;
          max-width: 490px;
          margin: 20px auto 0;
          text-align: center;
          border-top: 1px solid #e6ebf1;
        ">
            <p style="
            margin: 0;
            margin-top: 40px;
            font-size: 16px;
            font-weight: 600;
            color: #434343;
          ">
                HRIS SISTEM
            </p>

            <p style="margin: 0; margin-top: 16px; color: #434343;">
                Copyright © 2024 HRIS SISTEM. All rights reserved.
            </p>
        </footer>
    </div>
</body>

</html>