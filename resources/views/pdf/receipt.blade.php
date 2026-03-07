<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>দান রসিদ - {{ $donation->receipt_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Hind Siliguri', 'Arial', sans-serif;
            color: #333;
            line-height: 1.7;
            background: #f1f5f9;
            padding: 30px 20px;
        }

        .container {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
            background: white;
            border: 2px solid #2563eb;
            border-radius: 12px;
            overflow: hidden;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            padding: 28px 30px 22px;
            text-align: center;
        }

        .header .logo {
            height: 60px;
            width: auto;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 2px;
        }

        .receipt-title {
            background: rgba(255,255,255,0.15);
            display: inline-block;
            padding: 4px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 12px;
        }

        /* Body */
        .body { padding: 28px 30px; }

        /* Meta row */
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background: #f8faff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .meta-left { display: table-cell; vertical-align: middle; font-size: 13px; }
        .meta-right { display: table-cell; vertical-align: middle; text-align: right; font-size: 13px; }

        .meta-label { color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: block; }
        .meta-value { font-weight: 700; color: #1e3a8a; font-size: 13px; }

        /* Info table */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
        .info-table tr { border-bottom: 1px solid #f1f5f9; }
        .info-table tr:last-child { border-bottom: none; }
        .info-table td { padding: 9px 4px; font-size: 13px; vertical-align: top; }
        .info-table td:first-child { color: #64748b; font-weight: 600; width: 42%; }
        .info-table td:last-child { font-weight: 700; color: #1e293b; }

        /* Status badge */
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-pending   { background: #fef9c3; color: #854d0e; }
        .badge-default   { background: #e2e8f0; color: #475569; }

        /* Amount box */
        .amount-box {
            background: linear-gradient(135deg, #f0f9ff, #dbeafe);
            border: 1.5px solid #93c5fd;
            border-radius: 10px;
            padding: 18px;
            text-align: center;
            margin-bottom: 24px;
        }

        .amount-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .amount-value {
            font-size: 30px;
            font-weight: 800;
            color: #1e40af;
        }

        /* Thank you message */
        .thank-you {
            background: #f8faff;
            border-left: 4px solid #2563eb;
            border-radius: 0 8px 8px 0;
            padding: 14px 16px;
            font-size: 13px;
            color: #475569;
            line-height: 1.8;
            margin-bottom: 24px;
        }

        .thank-you strong { color: #1e3a8a; }

        /* Footer */
        .footer {
            background: #f8faff;
            border-top: 1px solid #dbeafe;
            padding: 16px 30px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }

        .footer strong { color: #475569; }

        @media print {
            body { background: white; padding: 0; }
            .container { border: 1.5px solid #2563eb; }
        }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        @if(isset($settings) && ($settings->site_logo ?? null))
            <img src="{{ public_path('storage/' . $settings->site_logo) }}" class="logo" alt="Logo">
        @endif
        <h1>{{ $settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন' }}</h1>
        @php
            $phone = $settings->phone ?? null;
            $address = $settings->address ?? null;
        @endphp
        @if($address || $phone)
        <p>
            @if($address){{ $address }}@endif
            @if($address && $phone) | @endif
            @if($phone){{ $settings->country_code ?? '+880' }} {{ $phone }}@endif
        </p>
        @endif
        @if($settings->email ?? null)
        <p>{{ $settings->email }}</p>
        @endif
        <div class="receipt-title">দান রসিদ</div>
    </div>

    <div class="body">

        {{-- Date + Receipt No --}}
        <div class="meta-row">
            <div class="meta-left">
                <span class="meta-label">তারিখ</span>
                <span class="meta-value">{{ $donation->created_at->format('d M, Y') }}</span>
            </div>
            <div class="meta-right">
                <span class="meta-label">রসিদ নম্বর</span>
                <span class="meta-value">#{{ $donation->receipt_no }}</span>
            </div>
        </div>

        {{-- Donor Info --}}
        <table class="info-table">
            <tr>
                <td>দাতার নাম</td>
                <td>{{ $donation->donor_name }}</td>
            </tr>
            <tr>
                <td>মোবাইল নম্বর</td>
                <td>{{ $settings->country_code ?? '+880' }} {{ $donation->donor_phone }}</td>
            </tr>
            <tr>
                <td>পেমেন্ট পদ্ধতি</td>
                <td>{{ strtoupper($donation->payment_method) }}</td>
            </tr>
            @if($donation->project)
            <tr>
                <td>সহযোগিতাকৃত প্রকল্প</td>
                <td>{{ $donation->project->name }}</td>
            </tr>
            @endif
            <tr>
                <td>স্ট্যাটাস</td>
                <td>
                    @php $status = strtolower($donation->status ?? 'pending'); @endphp
                    @if($status === 'completed')
                        <span class="badge badge-completed">সম্পন্ন</span>
                    @elseif($status === 'pending')
                        <span class="badge badge-pending">প্রক্রিয়াধীন</span>
                    @else
                        <span class="badge badge-default">{{ ucfirst($donation->status) }}</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Amount --}}
        <div class="amount-box">
            <div class="amount-label">মোট দানের পরিমাণ</div>
            <div class="amount-value">৳ {{ number_format($donation->amount, 2) }}</div>
        </div>

        {{-- Thank You --}}
        <div class="thank-you">
            <strong>{{ $donation->donor_name }}</strong> মহোদয়,<br>
            আপনার মূল্যবান অনুদানের জন্য আন্তরিক ধন্যবাদ ও কৃতজ্ঞতা জানাই।
            আপনার এই সহযোগিতা আমাদের একটি সুন্দর আগামী গড়ার পথে অনুপ্রেরণা দেয়।
            অনুগ্রহ করে এই রসিদটি আপনার রেকর্ডের জন্য সংরক্ষণ করুন।
            আমাদের টিম যাচাই সম্পন্ন করার পরে আপনার দানের স্ট্যাটাস "সম্পন্ন" হিসেবে আপডেট করা হবে।
        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>এটি একটি কম্পিউটার-জেনারেটেড রসিদ। কোনো ভৌত স্বাক্ষরের প্রয়োজন নেই।</p>
        <p style="margin-top:4px;">&copy; {{ date('Y') }} <strong>{{ $settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন' }}</strong>। সর্বস্বত্ব সংরক্ষিত।</p>
    </div>

</div>
</body>
</html>