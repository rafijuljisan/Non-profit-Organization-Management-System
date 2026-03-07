<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>ID Card - {{ $user->member_id ?? 'N/A' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Hind Siliguri', Arial, sans-serif;
        }

        body {
            background: #dde3ea;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            gap: 20px;
        }

        button.print-btn {
            background: #1d4ed8;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        /* ===== CARD ===== */
        .id-card {
            width: 320px;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0,0,0,0.25);
            background: white;
        }

        /* ===== HEADER ===== */
        .card-header {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            padding: 14px 16px;
        }

        .header-inner {
            display: table;
            width: 100%;
        }

        .header-logo {
            display: table-cell;
            width: 48px;
            vertical-align: middle;
        }

        .logo-box {
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-box img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            display: block;
        }

        .logo-initials {
            font-size: 9px;
            font-weight: 900;
            color: #1e3a8a;
            text-align: center;
            line-height: 1.3;
            padding: 2px;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
            color: white;
        }

        .org-name {
            font-size: 12.5px;
            font-weight: 700;
            line-height: 1.3;
        }

        .card-type {
            font-size: 8px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            opacity: 0.65;
            margin-top: 2px;
            font-family: Arial, sans-serif;
        }

        /* ===== PHOTO SECTION ===== */
        .card-photo-section {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            padding: 4px 16px 16px;
        }

        .photo-row {
            display: table;
            width: 100%;
        }

        .photo-cell {
            display: table-cell;
            width: 84px;
            vertical-align: bottom;
        }

        .photo-frame {
            width: 78px;
            height: 94px;
            border-radius: 8px;
            border: 3px solid rgba(255,255,255,0.5);
            overflow: hidden;
            background: rgba(255,255,255,0.15);
        }

        .photo-frame img {
            width: 78px;
            height: 94px;
            object-fit: cover;
            display: block;
        }

        .no-photo {
            width: 78px;
            height: 94px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.1);
        }

        .info-cell {
            display: table-cell;
            vertical-align: bottom;
            padding-left: 12px;
            color: white;
        }

        .member-name {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .member-id-pill {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.35);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #bfdbfe;
            margin-bottom: 8px;
            font-family: Arial, sans-serif;
        }

        .district-label {
            display: block;
            font-size: 8px;
            opacity: 0.6;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1px;
            font-family: Arial, sans-serif;
        }

        .district-name {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: Arial, sans-serif;
        }

        .s-active    { background: #bbf7d0; color: #14532d; }
        .s-pending   { background: #fef9c3; color: #854d0e; }
        .s-suspended { background: #fee2e2; color: #7f1d1d; }
        .s-inactive  { background: #e5e7eb; color: #374151; }

        /* ===== DETAILS ===== */
        .card-details {
            background: white;
            padding: 14px 16px 10px;
        }

        .detail-row {
            display: table;
            width: 100%;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-key {
            display: table-cell;
            width: 64px;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            font-weight: 700;
            vertical-align: middle;
            font-family: Arial, sans-serif;
        }

        .detail-val {
            display: table-cell;
            font-size: 11.5px;
            font-weight: 700;
            color: #1e3a8a;
            vertical-align: middle;
        }

        /* ===== FOOTER ===== */
        .card-footer {
            background: #f8faff;
            border-top: 1px dashed #dbeafe;
            padding: 10px 16px 12px;
            display: table;
            width: 100%;
        }

        .sig-cell {
            display: table-cell;
            vertical-align: bottom;
        }

        .sig-line {
            width: 90px;
            height: 1px;
            background: #1e3a8a;
            margin-bottom: 3px;
        }

        .sig-text {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            font-family: Arial, sans-serif;
        }

        .valid-cell {
            display: table-cell;
            text-align: right;
            vertical-align: bottom;
        }

        .valid-label {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            font-family: Arial, sans-serif;
        }

        .valid-val {
            font-size: 13px;
            font-weight: 800;
            color: #1e3a8a;
            font-family: Arial, sans-serif;
        }

        .bottom-bar {
            height: 6px;
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
        }

        @media print {
            body { background: white; padding: 0; }
            .print-btn { display: none; }
            .id-card { box-shadow: none; }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>

    <div class="id-card">

        {{-- HEADER --}}
        <div class="card-header">
            <div class="header-inner">
                <div class="header-logo">
                    <div class="logo-box">
                        @if(isset($settings) && ($settings->site_logo ?? null))
                            <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo">
                        @else
                            <div class="logo-initials">একতা<br>ফাউন্ডেশন</div>
                        @endif
                    </div>
                </div>
                <div class="header-text">
                    <div class="org-name">{{ $settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন' }}</div>
                    <div class="card-type">Identity Card</div>
                </div>
            </div>
        </div>

        {{-- PHOTO + INFO --}}
        <div class="card-photo-section">
            <div class="photo-row">
                <div class="photo-cell">
                    <div class="photo-frame">
                        @if($user->photo ?? null)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo">
                        @else
                            <div class="no-photo">&#9786;</div>
                        @endif
                    </div>
                </div>
                <div class="info-cell">
                    <div class="member-name">{{ $user->name }}</div>
                    <div class="member-id-pill">{{ $user->member_id ?? 'PENDING' }}</div>
                    <span class="district-label">District</span>
                    <span class="district-name">{{ $user->district->name ?? 'Central' }}</span>
                    @php
                        $sc = match($user->status ?? 'inactive') {
                            'active'    => 's-active',
                            'pending'   => 's-pending',
                            'suspended' => 's-suspended',
                            default     => 's-inactive',
                        };
                    @endphp
                    <span class="status-badge {{ $sc }}">{{ strtoupper($user->status ?? 'inactive') }}</span>
                </div>
            </div>
        </div>

        {{-- DETAILS --}}
        <div class="card-details">
            @if($user->phone ?? null)
            <div class="detail-row">
                <span class="detail-key">Phone</span>
                <span class="detail-val">{{ $user->phone }}</span>
            </div>
            @endif

            @if($user->email ?? null)
            <div class="detail-row">
                <span class="detail-key">Email</span>
                <span class="detail-val" style="font-size:10px;">{{ $user->email }}</span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-key">Joined</span>
                <span class="detail-val">{{ $user->created_at->format('d M Y') }}</span>
            </div>

            @if($settings->address ?? null)
            <div class="detail-row">
                <span class="detail-key">Address</span>
                <span class="detail-val" style="font-size:10px; color:#374151; font-weight:600;">{{ Str::limit($settings->address, 45) }}</span>
            </div>
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="card-footer">
            <div class="sig-cell">
                <div class="sig-line"></div>
                <div class="sig-text">Authorized Signature</div>
            </div>
            <div class="valid-cell">
                <div class="valid-label">Valid Until</div>
                <div class="valid-val">{{ now()->addYear()->format('M Y') }}</div>
            </div>
        </div>

        <div class="bottom-bar"></div>

    </div>

</body>
</html>