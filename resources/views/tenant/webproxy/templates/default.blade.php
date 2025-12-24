<!DOCTYPE html>
<html>
<head>
    <title>Akun Anda Kedaluwarsa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            padding: 50px;
            background: #f8fafc;
            color: #1e293b;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
        }
        h2 {
            color: #dc2626;
        }
        .btn {
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin: 20px 0;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛑 Akun Internet Anda Telah Kedaluwarsa</h2>
        <p>Silakan perpanjang paket Anda untuk melanjutkan akses.</p>
        <a href="https://{{ config('app.domain') }}/invoice/check?code={username}" class="btn">Perpanjang Sekarang</a>
        <p><small>Gunakan username Anda sebagai kode.</small></p>
    </div>
</body>
</html>