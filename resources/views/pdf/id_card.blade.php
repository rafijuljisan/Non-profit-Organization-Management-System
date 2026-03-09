<!DOCTYPE html>
<html lang="bn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ID Card - {{ $user->member_id ?? 'N/A' }}</title>
    <style>
        @font-face {
            font-family: 'solaimanlipi';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path("fonts/SolaimanLipi.ttf") }}') format('truetype');
        }

        body {
            font-family: 'solaimanlipi', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* The Main Card Container */
        .id-card {
            width: 260px; /* Standard vertical ID width */
            height: 410px; /* Standard vertical ID height */
            border: 2px solid #e8a020; /* Brand Orange */
            border-radius: 10px;
            margin: 0 auto;
            background-color: #ffffff;
            overflow: hidden;
            page-break-inside: avoid;
        }

        /* Top Header Area */
        .header {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 12px 5px;
            border-bottom: 4px solid #e8a020;
        }
        .org-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }
        .org-sub {
            font-size: 11px;
            color: #e8a020;
            letter-spacing: 1px;
            margin-top: 3px;
            font-weight: bold;
        }

        /* Profile Photo Area */
        .photo-area {
            text-align: center;
            padding-top: 15px;
            padding-bottom: 5px;
        }
        .photo-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #e8a020;
        }

        /* Name Area */
        .name-area {
            text-align: center;
            margin-bottom: 12px;
        }
        .user-name {
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            margin: 0;
        }
        .user-title {
            font-size: 13px;
            color: #555555;
            margin: 2px 0 0 0;
        }

        /* Information Table */
        .info-table {
            width: 85%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 0;
            font-size: 12px;
            color: #222222;
            vertical-align: middle;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        .info-colon {
            width: 5%;
            font-weight: bold;
        }
        .info-value {
            width: 55%;
            font-weight: bold;
        }

        /* QR and Signature Area */
        .bottom-table {
            width: 85%;
            margin: 15px auto 0 auto;
            border-collapse: collapse;
        }
        .qr-placeholder {
            width: 50px;
            height: 50px;
            border: 1px solid #cccccc;
            text-align: center;
            line-height: 50px;
            font-size: 10px;
            color: #777777;
        }
        .sig-line {
            border-bottom: 1px solid #000000;
            width: 90px;
            margin: 0 auto 3px auto;
        }
        .sig-text {
            font-size: 11px;
            color: #000000;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 8px 0;
            font-size: 11px;
            margin-top: 12px;
            border-top: 4px solid #e8a020;
        }
    </style>
</head>
<body>

    <div class="id-card">
        
        {{-- Header --}}
        <div class="header">
            <div class="org-name">{{ $settings->site_name ?? 'একতা জনকল্যাণ ফাউন্ডেশন' }}</div>
            <div class="org-sub">IDENTITY CARD</div>
        </div>

        {{-- Profile Photo --}}
        <div class="photo-area">
            @if(($user->photo ?? null) && file_exists(public_path('storage/' . $user->photo)))
                <img class="photo-img" src="{{ public_path('storage/' . $user->photo) }}" alt="Photo">
            @else
                {{-- Fallback image if no photo exists --}}
                <img class="photo-img" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=e8a020&color=ffffff&size=200" alt="Photo">
            @endif
        </div>

        {{-- Name & Title --}}
        <div class="name-area">
            <div class="user-name">{{ $user->name }}</div>
            <div class="user-title">{{ $user->designation ?? 'সদস্য/Member' }}</div>
        </div>

        {{-- Information Details --}}
        <table class="info-table">
            @if($user->member_id ?? null)
            <tr>
                <td class="info-label">আইডি নং</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $user->member_id }}</td>
            </tr>
            @endif
            <tr>
                <td class="info-label">যোগদানের তারিখ</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $user->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="info-label">রক্তের গ্রুপ</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $user->blood_group ?? 'Unknown' }}</td>
            </tr>
            @if($user->phone ?? null)
            <tr>
                <td class="info-label">ফোন</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $user->phone }}</td>
            </tr>
            @endif
            <tr>
                <td class="info-label">ঠিকানা</td>
                <td class="info-colon">:</td>
                <td class="info-value">{{ $user->district->name ?? 'Unknown' }}</td>
            </tr>
        </table>

        {{-- Signature & QR Section --}}
        <table class="bottom-table">
            <tr>
                <td style="width: 50%; text-align: left;">
                    <div class="qr-placeholder">QR Code</div>
                </td>
                <td style="width: 50%; text-align: center; vertical-align: bottom;">
                    <div class="sig-line"></div>
                    <div class="sig-text">কর্তৃপক্ষের স্বাক্ষর</div>
                </td>
            </tr>
        </table>

        {{-- Footer --}}
        <div class="footer">
            {{ now()->format('Y') }} - {{ now()->addYear()->format('Y') }} পর্যন্ত বৈধ
        </div>

    </div>

</body>
</html>