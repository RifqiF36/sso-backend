<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4F46E5;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-box {
            background-color: #f8f9fa;
            border: 2px dashed #4F46E5;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #4F46E5;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        p {
            color: #374151;
            line-height: 1.6;
            margin: 15px 0;
        }
        .warning {
            color: #dc2626;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✉️ Verifikasi Email</h1>
        </div>
        <div class="content">
            <p>Halo{{ $userName ? ' ' . $userName : '' }},</p>
            <p>Terima kasih telah mendaftar! Gunakan kode OTP berikut untuk memverifikasi email Anda:</p>
            
            <div class="otp-box">
                <div style="color: #6b7280; font-size: 14px; margin-bottom: 10px;">Kode Verifikasi Anda</div>
                <div class="otp-code">{{ $otp }}</div>
                <div style="color: #6b7280; font-size: 12px; margin-top: 10px;">Berlaku selama 10 menit</div>
            </div>

            <p>Masukkan kode ini pada halaman verifikasi untuk melanjutkan proses pendaftaran.</p>
            
            <p class="warning">⚠️ Jangan bagikan kode ini kepada siapapun. Tim kami tidak akan pernah meminta kode OTP Anda.</p>
            
            <p>Jika Anda tidak melakukan pendaftaran, abaikan email ini.</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} SSO Backend. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
