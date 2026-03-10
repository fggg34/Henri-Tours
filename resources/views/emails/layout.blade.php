<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name'))</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #374151; margin: 0; padding: 0; background: #f3f4f6; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1); overflow: hidden; }
        .email-table { width: 100%; border-collapse: collapse; }
        .detail-row { border-bottom: 1px solid #e5e7eb; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { padding: 12px 16px; width: 140px; font-size: 13px; color: #6b7280; font-weight: 500; vertical-align: top; }
        .detail-value { padding: 12px 16px; font-size: 14px; color: #111827; }
        .footer { padding: 20px 24px; background: #f9fafb; font-size: 12px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; }
        .btn { display: inline-block; padding: 12px 24px; background: #65a30d; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; margin-top: 8px; }
        .btn:hover { background: #4d7c0f; }
        .muted { color: #6b7280; font-size: 13px; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <div class="card">
            @yield('header')
            <div style="padding: 24px 24px 0;">
                @yield('content')
            </div>
            @hasSection('footer')
                @yield('footer')
            @else
                <div class="footer">
                    Best regards,<br><strong>{{ config('app.name') }}</strong>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
