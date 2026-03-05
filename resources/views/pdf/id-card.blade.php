<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ID Card - {{ $user->member_id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; }
        .id-card { 
            width: 300px; 
            border: 2px solid #1a56db; 
            padding: 20px; 
            text-align: center; 
            margin: 0 auto; 
            border-radius: 10px;
            background-color: #f8fafc;
        }
        .org-name { font-size: 22px; font-weight: bold; margin-bottom: 5px; color: #1a56db; }
        .title { font-size: 14px; background-color: #1a56db; color: white; padding: 5px; border-radius: 5px; margin-bottom: 15px; }
        .member-name { font-size: 20px; font-weight: bold; margin-top: 10px; }
        .member-id { font-size: 16px; color: #ef4444; font-weight: bold; margin-bottom: 15px; }
        .info { text-align: left; font-size: 14px; line-height: 1.8; margin-top: 10px;}
        .footer { margin-top: 30px; font-size: 12px; border-top: 1px solid #ccc; padding-top: 10px; text-align: right; font-style: italic;}
    </style>
</head>
<body>
    <div class="id-card">
        <div class="org-name">NGO Foundation</div>
        <div class="title">IDENTITY CARD</div>
        
        <div class="member-name">{{ $user->name }}</div>
        <div class="member-id">{{ $user->member_id ?? 'N/A' }}</div>
        
        <div class="info">
            <strong>District:</strong> {{ $user->district ? $user->district->name : 'Central' }}<br>
            <strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}<br>
            <strong>Status:</strong> <span style="text-transform: uppercase;">{{ $user->status }}</span>
        </div>
        
        <div class="footer">
            <strong>Authorized Signature</strong>
        </div>
    </div>
</body>
</html>