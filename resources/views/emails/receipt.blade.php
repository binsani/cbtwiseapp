<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CBTWise Payment Receipt</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; padding: 40px 0; }
        .content { max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); overflow: hidden; }
        .header { background-color: #0F7B3E; padding: 32px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; tracking-wide: 1px; }
        .body { padding: 40px; }
        .body h2 { font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; }
        .body p { font-size: 15px; line-height: 1.6; color: #475569; }
        .table { width: 100%; border-collapse: collapse; margin: 24px 0; }
        .table th, .table td { padding: 12px; border-bottom: 1px solid #f1f5f9; text-align: left; font-size: 14px; }
        .table th { font-weight: 700; color: #64748b; }
        .table td { color: #0f172a; }
        .btn { display: inline-block; padding: 14px 32px; background-color: #0F7B3E; color: #ffffff !important; font-weight: 700; text-decoration: none; border-radius: 12px; margin-top: 16px; text-align: center; }
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
                            <h2>Subscription Upgraded Successfully!</h2>
                            <p>Hello {{ $payment->user->name }},</p>
                            <p>Thank you for subscribing to CBTWise Premium. Your payment has been processed successfully, and your account features have been unlocked.</p>
                            
                            <table class="table">
                                <tr>
                                    <th>Reference</th>
                                    <td>{{ $payment->paystack_reference }}</td>
                                </tr>
                                <tr>
                                    <th>Amount Paid</th>
                                    <td>₦{{ number_format($payment->amount_kobo / 100, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Duration</th>
                                    <td>{{ $payment->plan_duration_days }} Days</td>
                                </tr>
                                <tr>
                                    <th>Expires On</th>
                                    <td>{{ $payment->user->premium_expires_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>

                            <p>You now have full access to our comprehensive UTME/SSCE timed mock exams, weak topic diagnostics, and premium AI explanations.</p>
                            
                            <center>
                                <a href="{{ route('dashboard') }}" class="btn">Launch Dashboard</a>
                            </center>
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
