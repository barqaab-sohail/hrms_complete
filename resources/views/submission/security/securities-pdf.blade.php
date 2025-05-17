<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Bid Security Type</th>
                <th>Favor Of</th>
                <th>Date Issued</th>
                <th>Expiry Date</th>
                <th class="text-right">Amount</th>
                <th>Project Name</th>
                <th>Status</th>
                <th>Client</th>
                <th>Bank</th>
            </tr>
        </thead>
        <tbody>
            @foreach($securities as $security)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $security->type)) }}</td>
                <td>{{ $security->bid_security_type ? ucfirst(str_replace('_', ' ', $security->bid_security_type)) : '-' }}</td>
                <td>{{ $security->favor_of }}</td>
                <td>{{ $security->date_issued->format('Y-m-d') }}</td>
                <td>{{ $security->expiry_date ? $security->expiry_date->format('Y-m-d') : '-' }}</td>
                <td class="text-right">{{ number_format($security->amount, 2) }}</td>
                <td>{{ $security->project_name }}</td>
                <td>{{ ucfirst($security->status) }}</td>
                <td>{{ $security->client->name ?? '-' }}</td>
                <td>{{ $security->bank->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($securities->count() > 15)
    <div class="page-break"></div>
    @endif
</body>

</html>