<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Receipt</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; margin: 0 auto; padding: 20px; border: 2px solid #2563eb; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .receipt-info { margin-bottom: 30px; }
        .receipt-info td { padding: 5px; }
        .amount-box { background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 15px; text-align: center; margin-bottom: 30px; border-radius: 5px; }
        .amount-box h2 { margin: 0; color: #1e40af; font-size: 24px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { display: inline-block; padding: 3px 8px; background: #fef08a; color: #854d0e; border-radius: 12px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NGO Foundation</h1>
            <p>123 Charity Lane, Dhaka, Bangladesh | +880 1234 567890</p>
            <h3 style="margin-top: 20px; text-decoration: underline;">DONATION RECEIPT</h3>
        </div>

        <table class="receipt-info" width="100%">
            <tr>
                <td width="50%"><strong>Date:</strong> {{ $donation->created_at->format('d M, Y') }}</td>
                <td width="50%" style="text-align: right;"><strong>Receipt No:</strong> {{ $donation->receipt_no }}</td>
            </tr>
            <tr>
                <td><strong>Donor Name:</strong> {{ $donation->donor_name }}</td>
                <td style="text-align: right;"><strong>Status:</strong> <tr>
                <td><strong>Donor Name:</strong> {{ $donation->donor_name }}</td>
                <td style="text-align: right;">
                    <strong>Status:</strong> 
                    @if(strtolower($donation->status) === 'completed')
                        <span class="badge" style="background: #dcfce3; color: #166534;">Completed</span>
                    @else
                        <span class="badge">{{ ucfirst($donation->status) }}</span>
                    @endif
                </td>
            </tr></td>
            </tr>
            <tr>
                <td><strong>Phone:</strong> {{ $donation->donor_phone }}</td>
                <td style="text-align: right;"><strong>Payment Method:</strong> {{ strtoupper($donation->payment_method) }}</td>
            </tr>
            @if($donation->project)
            <tr>
                <td colspan="2"><strong>Project Supported:</strong> {{ $donation->project->name }}</td>
            </tr>
            @endif
        </table>

        <div class="amount-box">
            <p style="margin: 0; color: #64748b; text-transform: uppercase; font-weight: bold; font-size: 14px;">Total Amount Donated</p>
            <h2>BDT {{ number_format($donation->amount, 2) }}</h2>
        </div>

        <p>Dear {{ $donation->donor_name }},</p>
        <p>Thank you so much for your generous donation. Your contribution helps us build a better future. Please keep this receipt for your records. Your donation status will be updated to "Completed" once the transaction is verified by our team.</p>

        <div class="footer">
            <p>This is a computer-generated receipt and does not require a physical signature.</p>
            <p>&copy; {{ date('Y') }} NGO Foundation. All rights reserved.</p>
        </div>
    </div>
</body>
</html>