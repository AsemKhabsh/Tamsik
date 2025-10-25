<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم الإجابة على سؤالك</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1d8a4e 0%, #155a33 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            background-color: #f8f9fa;
            border-right: 4px solid #1d8a4e;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .fatwa-title {
            font-size: 20px;
            color: #1d8a4e;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .scholar-info {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 4px;
        }
        .scholar-icon {
            width: 50px;
            height: 50px;
            background-color: #1d8a4e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-left: 15px;
        }
        .scholar-name {
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #1d8a4e 0%, #155a33 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background: linear-gradient(135deg, #155a33 0%, #1d8a4e 100%);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #1d8a4e;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">📖</div>
            <h1>تم الإجابة على سؤالك</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                السلام عليكم ورحمة الله وبركاته
            </div>

            <p style="color: #555; line-height: 1.8;">
                نود إعلامك بأنه تم الإجابة على سؤالك في موقع تمسك.
            </p>

            <div class="message">
                <div class="fatwa-title">{{ $fatwa->title }}</div>
                <p style="color: #666; margin: 10px 0;">
                    <strong>السؤال:</strong><br>
                    {{ Str::limit(strip_tags($fatwa->question), 150) }}
                </p>
            </div>

            <div class="scholar-info">
                <div class="scholar-icon">
                    👨‍🎓
                </div>
                <div>
                    <div style="color: #666; font-size: 14px;">أجاب عليه</div>
                    <div class="scholar-name">{{ $fatwa->scholar->name }}</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('fatwas.show', $fatwa->id) }}" class="button">
                    اقرأ الإجابة الكاملة
                </a>
            </div>

            <div class="divider"></div>

            <p style="color: #666; font-size: 14px; line-height: 1.6;">
                يمكنك الآن قراءة الإجابة الكاملة على سؤالك من خلال زيارة الموقع. 
                نسأل الله أن ينفعك بهذه الإجابة وأن يزيدك علماً وفهماً.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 10px 0;">
                <strong>موقع تمسك</strong><br>
                منصة إسلامية شاملة للفتاوى والخطب والمحاضرات
            </p>
            <p style="margin: 10px 0;">
                <a href="{{ url('/') }}">زيارة الموقع</a> | 
                <a href="{{ route('fatwas.index') }}">الفتاوى</a> | 
                <a href="{{ route('profile') }}">حسابي</a>
            </p>
            <p style="margin: 10px 0; font-size: 12px; color: #999;">
                هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه.
            </p>
        </div>
    </div>
</body>
</html>

