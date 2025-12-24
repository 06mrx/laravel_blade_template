<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Internet Anda Kedaluwarsa</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3730a3 50%, #5b21b6 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .logo {
            max-height: 50px;
            margin-bottom: 12px;
        }
        .business-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .tagline {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 4px;
        }
        .content {
            padding: 30px;
            text-align: center;
        }
        .icon {
            font-size: 48px;
            color: #dc2626;
            margin-bottom: 16px;
        }
        h1 {
            font-size: 22px;
            color: #1e293b;
            margin: 0 0 12px 0;
        }
        p {
            margin: 0 0 16px 0;
            font-size: 16px;
            color: #475569;
        }
        .highlight {
            font-weight: 600;
            color: #1e40af;
        }
        .invoice-info {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            text-align: left;
            font-size: 14px;
        }
        .invoice-info p {
            margin: 4px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 32px;
            margin: 20px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5);
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #64748b;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header dengan Logo & Nama Usaha -->
        <div class="header">
            @if($configuration->business_logo)
                <img src="{{ $configuration->business_logo }}" alt="{{ $configuration->business_name }}" class="logo">
            @else
                <div style="width: 100%; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 24px; font-weight: bold;">{{ $configuration->business_name }}</span>
                </div>
            @endif
            <p class="business-name">{{ $configuration->business_name }}</p>
            <p class="tagline">Sistem Manajemen Jaringan Profesional</p>
        </div>

        <!-- Konten Utama -->
        <div class="content">
            <div class="icon">⚠️</div>
            <h1>Akun Internet Anda Telah Kedaluwarsa</h1>
            <p>Halo <strong>{{ $customer->name }}</strong>,</p>
            <p>Akun internet Anda telah <strong>berakhir pada {{ $customer->expired_at }}</strong> dan saat ini tidak dapat mengakses internet.</p>

            <!-- Informasi Invoice -->
            <div class="invoice-info">
                <p><strong>📌 Nomor Invoice:</strong> <span class="highlight">{{ $invoice->invoice_number }}</span></p>
                <p><strong>📅 Tanggal Jatuh Tempo:</strong> {{ $invoice->due_date }}</p>
                <p><strong>💰 Jumlah:</strong> <span class="highlight">Rp{{ number_format($invoice->amount, 0, ',', '.') }}</span></p>
            </div>

            <p>Silakan perpanjang paket Anda untuk melanjutkan layanan.</p>

            <!-- Tombol Perpanjang -->
            <a href="{{ env('APP_URL') . "invoices/check?code=" . $invoice->invoice_number }}" class="btn">Perpanjang Sekarang</a>
            {{-- <a href="{{ $invoiceUrl }}" class="btn">Perpanjang Sekarang</a> --}}

            <p style="font-size: 14px; color: #64748b;">
                Jika tombol di atas tidak bekerja, salin dan tempel tautan berikut:<br>
                <a href="{{ env('APP_URL') . "invoices/check?code=" . $invoice->invoice_number }}" style="color: #3b82f6;">{{ env('APP_URL') . "invoices/check?code=" . $invoice->invoice_number }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                &copy; {{ now()->year }} {{ $configuration->business_name }}. Hak cipta dilindungi.<br>
                Dikelola oleh <a href="{{ env('APP_URL')}}" target="_blank">{{env('APP_NAME')}}</a> — Sistem Manajemen ISP.
            </p>
        </div>
    </div>
</body>
</html>