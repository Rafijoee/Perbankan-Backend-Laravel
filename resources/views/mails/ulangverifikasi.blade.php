<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi 2FA</title>
</head>
<body>
    <p>Halo {{ $name }},</p>
    <p>Kami telah menerima permintaan untuk mengirim ulang kode 2FA.</p>
    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #6A1B9A; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #BDBDBD; border-radius: 8px; background-color: #FFFFFF;">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://ik.imagekit.io/itsbibbb/TiketGo/logo.png?updatedAt=1734801819539" alt="TiketGo Logo" style="max-width: 150px; height: auto;">
        </div>
        <h2 style="text-align: center; color: #6A1B9A;">Kode Verifikasi 2FA Anda</h2>
        <p>Untuk melanjutkan proses yang anda lakukan, gunakan kode berikut:</p>
        <div style="text-align: center; margin: 20px 0;">
            <span style="font-size: 24px; font-weight: bold; padding: 10px 20px; background-color: #FFE082; border: 1px solid #BDBDBD; border-radius: 4px; display: inline-block; color: #6A1B9A;">
                {{ $token }}
            </span>
        </div>
        <p>Kode ini akan kedaluwarsa dalam <strong>5 menit</strong>. Jika Anda tidak meminta untuk mengirim ulang kode 2FA, abaikan email ini.</p>
        <p>Salam hangat,<br>
        <strong>Tim TiketGo</strong></p>
        <hr style="border: 0; border-top: 1px solid #BDBDBD; margin: 20px 0;">
        <p style="font-size: 12px; color: #6A1B9A;">Jika Anda mengalami masalah, silakan hubungi 
            <a href="mailto:support@example.com" style="color: #6A1B9A;">support@example.com</a>.
        </p>
    </div>
</body>
</html>
