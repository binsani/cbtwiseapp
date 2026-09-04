<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CBTWise Subscription Expiring</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding: 40px 0; }
        .content { max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); overflow: hidden; }
        .header { background-color: #d97706; padding: 32px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; }
        .body { padding: 40px; }
        .body h2 { font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; }
        .body p { font-size: 15px; line-height: 1.6; color: #475569; }
        .btn { display: inline-block; padding: 14px 32px; background-color: #d97706; color: #ffffff !important; font-weight: 700; text-decoration: none; border-radius: 12px; margin-top: 16px; text-align: center; }
        .footer { text-align: center; padding: 32px; font-size: 12px; color: #94a3b8; line-height: 1.5; }
    </style>
</head>
<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="content" cellpadding="0" cellspacing="0">
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            <h1>CBTWise</h1>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td class="body">
                            <h2>Your Premium Access is Expiring Soon</h2>
                            <p>Hello {{ $user->name }},</p>
                            <p>This is a friendly reminder that your CBTWise Premium subscription is expiring in **3 days** on **{{ $user->premium_expires_at->format('M d, Y') }}**.</p>
                            <p>To avoid losing your study streaks, customized weak-topic study plans, and advanced detailed reviews, please click the button below to renew your plan today.</p>
                            
                            <center>
                                <a href="{{ route('pricing') }}" class="btn">Renew Subscription</a>
                            </center>
                            
                            <p class="mt-4 text-xs text-gray-400">If you have recently renewed, please ignore this email.</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p>© {{ date('Y') }} CBTWise. All rights reserved.</p>
                            <p>If you have any questions, please contact our support desk.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
