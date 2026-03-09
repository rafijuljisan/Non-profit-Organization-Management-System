<!DOCTYPE html>
<html lang="bn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Donation Receipt - {{ $donation->receipt_no ?? '' }}</title>
    <style>
        * { font-family: 'solaimanlipi', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-size: 15px; 
            color: #111;
            background: #fff;
            line-height: 1.5;
        }

        /* ── Receipt Block ── */
        .receipt {
            width: 100%;
            border: 2px solid #222;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        /* ── Header ── */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #eee;
            padding: 15px 20px 10px 20px;
            background: #fdfdfd;
        }
        
        .slogan { font-size: 14px; color: #eb6119; font-style: italic; margin-bottom: 5px; }
        .org-name { font-size: 34px; font-weight: bold; color: #111; margin-bottom: 5px; line-height: 1.1; } 
        .org-address { font-size: 14px; color: #eb6119; font-weight: bold; margin-bottom: 5px; }
        .established { font-size: 13px; color: #555; margin-bottom: 8px; }
        .receipt-badge {
            background: #eb6119; color: #fff;
            padding: 5px 25px; border-radius: 20px; 
            font-size: 15px; font-weight: bold;
            display: inline-block;
        }

        /* ── Body ── */
        .receipt-body { 
            padding: 20px 25px 25px 25px; 
        }

        /* Data Tables */
        .data-table { width: 100%; margin-bottom: 12px; border-collapse: collapse; }
        .data-table td { padding: 6px 0; vertical-align: bottom; } 
        
        .label { font-weight: bold; white-space: nowrap; padding-right: 5px; font-size: 18px;}
        
        .dotted-line { 
            border-bottom: 1.5px dotted #333; 
            padding-left: 5px; 
            color: #000; 
            font-size: 18px;
            font-weight: bold; 
        }

        /* Amount box */
        .amount-box {
            border: 2px solid #222; padding: 4px 15px; 
            display: inline-block; min-width: 130px; 
            font-weight: bold; font-size: 20px; 
            border-radius: 5px; text-align: center;
            background: #f9f9f9;
        }

        /* 🚀 FIXED: Perfect Checkboxes */
        .check-box { 
            display: inline-block; 
            width: 16px; 
            height: 16px; 
            border: 1.5px solid #222; 
            vertical-align: middle; 
            margin-right: 6px; 
            text-align: center;
            line-height: 16px;
            font-size: 16px;
            font-family: 'freeserif', sans-serif; /* টিক চিহ্নের জন্য ফন্ট */
            font-weight: bold;
            color: #111;
        }
        .check-item { 
            display: inline-block; 
            margin-right: 20px; 
            font-weight: bold; 
            font-size: 16px; 
        }

        /* Signature */
        .signature-line {
            border-top: 1.5px solid #333; display: inline-block; width: 160px;
            padding-top: 5px; text-align: center; font-size: 15px; font-weight: bold;
        }

        /* Footer */
        .footer-bar {
            background-color: #1f2937; color: #fff; padding: 10px 15px;
            font-size: 13px; text-align: center;
        }
        .footer-icon { color: #eb6119; font-weight: bold; margin-right: 5px; }
        .footer-item { display: inline-block; margin: 0 12px; }

        /* Copy label */
        .copy-label { text-align: right; font-size: 12px; color: #555; margin-bottom: 2px; font-weight: bold; }

        /* Cut line */
        .cut-line { border-top: 1px dashed #aaa; margin: 15px 0; text-align: center; }
        .cut-text {
            display: inline-block; background: #fff; padding: 0 10px;
            font-size: 12px; color: #888; position: relative; top: -10px;
        }
    </style>
</head>
<body>

@php
    use Carbon\Carbon;

    $receiptNo   = $donation->receipt_no ?? '';
    $date        = isset($donation->created_at) ? Carbon::parse($donation->created_at)->format('d/m/Y') : '';
    $name        = $donation->donor_name ?? '';
    $phone       = $donation->donor_phone ?? '—';
    $address     = $donation->donor_address ?? '';
    $amount      = isset($donation->amount) ? number_format($donation->amount, 0) : '';
    $projectName = $donation->project->name ?? '';
    $amountInWords = $donation->amount_in_words ?? '';

    // Checkbox logic
    $isGeneral = empty($donation->project_id);
    $isEid     = !empty($donation->project_id) && (str_contains(strtolower($projectName), 'eid') || str_contains($projectName, 'ঈদ'));
    $isOther   = !empty($donation->project_id) && !$isEid;

    $logoPath = public_path('images/logo.png');
@endphp

@foreach(['অফিস কপি (Office Copy)', 'দাতা কপি (Donor Copy)'] as $copyLabel)

<div class="copy-label">{{ $copyLabel }}</div>
<div class="receipt">

    {{-- ══ Header ══ --}}
    <table class="header-table">
        <tr>
            <td width="20%" align="center" valign="middle">
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" style="width: 100px; height: 100px;">
                @endif
            </td>
            <td width="80%" align="center" valign="middle">
                <div class="slogan">শান্তির পথে, মানবতার সাথে</div>
                <div class="org-name">একতা জনকল্যাণ ফাউন্ডেশন</div>
                <div class="org-address">ভূষণা-লক্ষণদিয়া, মধুখালী, ফরিদপুর</div>
                <div class="established">স্থাপিত : ২০২২ খ্রি.</div>
                <div class="receipt-badge">অনুদান রশিদ (Donation Receipt)</div>
            </td>
        </tr>
    </table>

    {{-- ══ Body ══ --}}
    <div class="receipt-body">
        
        <table class="data-table">
            <tr>
                <td width="15%" class="label">ক্রমিক নং :</td>
                <td width="35%" class="dotted-line">{{ $receiptNo }}</td>
                <td width="15%" class="label" align="right">তারিখ :&nbsp;</td>
                <td width="35%" class="dotted-line">{{ $date }}</td>
            </tr>
        </table>

        <table class="data-table">
            <tr>
                <td width="10%" class="label">নাম :</td>
                <td width="90%" class="dotted-line" style="font-size: 20px;">{{ $name }}</td>
            </tr>
        </table>

        <table class="data-table">
            <tr>
                <td width="18%" class="label">মোবাইল নং :</td>
                <td width="30%" class="dotted-line">{{ $phone }}</td>
                <td width="12%" class="label" align="right">ঠিকানা :&nbsp;</td>
                <td width="40%" class="dotted-line">{{ $address }}</td>
            </tr>
        </table>

        <table class="data-table" style="margin-top: 15px; margin-bottom: 25px;">
            <tr>
                <td width="18%" class="label" valign="middle" style="font-size: 20px;">টাকার পরিমান :</td>
                <td width="22%" valign="middle"><div class="amount-box">{{ $amount }} /-</div></td>
                <td width="10%" class="label" align="right" valign="middle" style="font-size: 20px;">কথায় :&nbsp;</td>
                <td width="50%" class="dotted-line" valign="bottom" style="font-size: 20px;">{{ $amountInWords }}</td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td width="75%" valign="bottom">
                    <span class="check-item">
                        <span class="check-box">{!! $isGeneral ? '&#10003;' : '&nbsp;' !!}</span> সাধারণ
                    </span>
                    <span class="check-item">
                        <span class="check-box">{!! $isEid ? '&#10003;' : '&nbsp;' !!}</span> ঈদ সামগ্রী
                    </span>
                    <span class="check-item">
                        <span class="check-box">{!! $isOther ? '&#10003;' : '&nbsp;' !!}</span> অন্যান্য : 
                        <span style="border-bottom: 1.5px dotted #333; display: inline-block; width: 140px; padding-left: 5px; font-weight: bold;">{{ $isOther ? $projectName : '' }}</span>
                    </span>
                </td>
                <td width="25%" align="right" valign="bottom">
                    <div class="signature-line">গ্রহণকারীর স্বাক্ষর</div>
                </td>
            </tr>
        </table>

    </div>

    {{-- ══ Footer ══ --}}
    <div class="footer-bar">
        <span class="footer-item"><span class="footer-icon">&#9990;</span>+8801315-439198</span>
        <span class="footer-item"><span class="footer-icon">f</span>www.fb.com/foundation.ekota</span>
        <span class="footer-item"><span class="footer-icon">@</span>ekota.j.foundation@gmail.com</span>
    </div>

</div>

@if($loop->first)
<div class="cut-line">
    <span class="cut-text">✂ এখান থেকে কাটুন</span>
</div>
@endif

@endforeach

</body>
</html>