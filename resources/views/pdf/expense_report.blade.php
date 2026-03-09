<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Expense Report</title>
    <style>
        body {
            font-family: 'freeserif', sans-serif; /* mPDF এর ডিফল্ট বাংলা সাপোর্ট ফন্ট */
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555;
        }
        .report-info {
            margin-bottom: 15px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 10px;
            text-align: left;
            font-size: 13px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .status {
            font-weight: bold;
        }
        .status-completed { color: #16a34a; }
        .status-pending { color: #d97706; }
        .status-rejected { color: #dc2626; }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>একতা জনকল্যাণ ফাউন্ডেশন</h1>
        <p>অফিসিয়াল খরচের রিপোর্ট</p>
    </div>

    <div class="report-info">
        <strong>Report Generated On:</strong> {{ now()->format('d M, Y h:i A') }}<br>
        <strong>Total Records:</strong> {{ $records->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Project</th>
                <th>Date</th>
                <th>Status</th>
                <th class="text-right">Amount (৳)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            
            @forelse($records as $index => $row)
                @php 
                    if($row->status === 'completed') {
                        $totalAmount += $row->amount; 
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row->category }}</td>
                    <td>{{ $row->project->name ?? 'N/A' }}</td>
                    <td>{{ $row->created_at->format('d M, Y') }}</td>
                    <td class="status status-{{ $row->status }}">
                        {{ ucfirst($row->status) }}
                    </td>
                    <td class="text-right">{{ number_format($row->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No records found.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right" style="background-color: #e2e8f0; color: #333;">Total Approved Amount:</th>
                <th class="text-right" style="background-color: #e2e8f0; color: #1e3a8a; font-weight: bold;">
                    ৳ {{ number_format($totalAmount, 2) }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        This is a system-generated report. Unauthorized modification is prohibited.
    </div>

</body>
</html>