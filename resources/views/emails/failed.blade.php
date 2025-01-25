<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Başarısız Giriş Denemesii</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e7e7e7;
        }
        .email-header {
            background-color: #ff6b6b;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .email-content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-content p {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .email-details {
            background-color: #f4f6fa;
            padding: 15px;
            border-left: 4px solid #ff6b6b;
            margin: 20px 0;
            border-radius: 6px;
            font-size: 14px;
        }
        .email-details strong {
            font-weight: bold;
            color: #555555;
        }
        .email-footer {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            font-size: 14px;
            color: #777777;
            border-top: 1px solid #e7e7e7;
        }
        .email-footer a {
            color: #ff6b6b;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        Başarısız Giriş Denemesi!
    </div>
    <div class="email-content">
        <p>{{ config('app.name') }} hesabınıza başarısız bir giriş denemesi oldu.</p>
        <div class="email-details">
            <p><strong>Hesap:</strong> {{ $account->email }}</p>
            <p><strong>Zaman:</strong> {{ $time->toCookieString() }}</p>
            <p><strong>IP Adresi:</strong> {{ $ipAddress }}</p>
            <p><strong>Tarayıcı:</strong> {{ $browser }}</p>
            @if ($location && $location['default'] === false)
                <p><strong>Konum:</strong> {{ $location['city'] ?? 'Bilinmeyen Şehir' }}, {{ $location['state'] ?? 'Bilinmeyen Bölge' }}</p>
            @endif
        </div>
        <p>Eğer bu giriş denemesi size aitse, bu bildirimi göz ardı edebilirsiniz. Ancak, hesabınızda şüpheli bir etkinlik fark ederseniz, lütfen şifrenizi hemen değiştirin.</p>
    </div>
    <div class="email-footer">
        <p>Saygılarımızla,</p>
        <p><a href="{{ config('app.url') }}">{{ config('app.name') }}</a></p>
    </div>
</div>
</body>
</html>
