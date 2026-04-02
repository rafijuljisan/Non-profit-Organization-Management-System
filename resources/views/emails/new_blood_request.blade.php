<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; padding: 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; border-top: 5px solid #d32f2f; }
        h2 { color: #d32f2f; margin-top: 0; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .details-table th, .details-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .details-table th { background-color: #f9fafb; width: 40%; color: #374151; }
        .btn { display: inline-block; background: #d32f2f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>🩸 নতুন রক্তের আবেদন এসেছে!</h2>
        <p>সিস্টেমে একটি নতুন রক্তের রিকোয়েস্ট জমা পড়েছে। দয়া করে দ্রুত ব্যবস্থা নিন।</p>

        <table class="details-table">
            <tr>
                <th>রোগীর নাম</th>
                <td>{{ $bloodRequest->patient_name }}</td>
            </tr>
            <tr>
                <th>রক্তের গ্রুপ</th>
                <td style="color: red; font-weight: bold; font-size: 16px;">{{ $bloodRequest->donor->blood_group }}</td>
            </tr>
            <tr>
                <th>হাসপাতাল</th>
                <td>{{ $bloodRequest->hospital_name }}</td>
            </tr>
            <tr>
                <th>কত ব্যাগ প্রয়োজন?</th>
                <td>{{ $bloodRequest->bags_needed }} ব্যাগ</td>
            </tr>
            <tr>
                <th>আবেদনকারীর নাম</th>
                <td>{{ $bloodRequest->requester_name }}</td>
            </tr>
            <tr>
                <th>আবেদনকারীর ফোন</th>
                <td><a href="tel:{{ $bloodRequest->requester_phone }}">{{ $bloodRequest->requester_phone }}</a></td>
            </tr>
            <tr>
                <th>সিলেক্টেড ডোনার</th>
                <td>{{ $bloodRequest->donor->name }} ({{ $bloodRequest->donor->phone }})</td>
            </tr>
        </table>

        <a href="{{ url('/admin/blood-requests') }}" class="btn">অ্যাডমিন প্যানেলে দেখুন</a>
        
        <p style="margin-top: 20px; font-size: 12px; color: #6b7280;">এটি একটি স্বয়ংক্রিয় ইমেইল, এর রিপ্লাই দেওয়ার প্রয়োজন নেই।</p>
    </div>
</body>
</html>