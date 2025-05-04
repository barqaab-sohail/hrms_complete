<!DOCTYPE html>
<html>

<head>
    <title>Weekly Missing Documents Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .pec-employee {
            background-color: #fff8e1;
        }

        .no-data {
            color: #777;
            font-style: italic;
        }

        .document-list {
            margin: 0;
            padding-left: 20px;
        }

        .summary-table {
            width: auto;
            margin-bottom: 30px;
        }

        .summary-table th,
        .summary-table td {
            padding: 8px 15px;
        }
    </style>
</head>

<body>
    <h1>Weekly Missing Documents Report</h1>
    <p>Report generated on: {{ $reportDate }}</p>

    @if(!empty($missingDocuments))
    @php
    // Prepare division summary data
    $divisionSummary = [];
    foreach ($missingDocuments as $employee) {
    $division = $employee['division'] ?? 'Unknown';
    if (!isset($divisionSummary[$division])) {
    $divisionSummary[$division] = 0;
    }
    $divisionSummary[$division]++;
    }
    arsort($divisionSummary);
    @endphp

    <h2>Division-wise Summary</h2>
    <table class="summary-table">
        <tr>
            <th>Division</th>
            <th>Employees with Missing Documents</th>
        </tr>
        @foreach($divisionSummary as $division => $count)
        <tr>
            <td>{{ $division }}</td>
            <td>{{ $count }}</td>
        </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td>Total</td>
            <td>{{ count($missingDocuments) }}</td>
        </tr>
    </table>

    <h2>Detailed Report</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Employee #</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Joining Date</th>
            <th>Contact</th>
            <th>Division</th>
            <th>Project/Office</th>
            <th>Missing Documents</th>
        </tr>
        @foreach($missingDocuments as $index => $employee)
        <tr class="{{ $employee['is_pec'] ? 'pec-employee' : '' }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $employee['employee_no'] }}</td>
            <td>{{ $employee['employee_name'] }}</td>
            <td>{{ $employee['designation'] }}</td>
            <td>{{ $employee['joining_date'] }}</td>
            <td>{{ $employee['contact_number'] }}</td>
            <td>{{ $employee['division'] }}</td>
            <td>{{ $employee['project'] }}</td>
            <td>
                <ul class="document-list">
                    @foreach($employee['missing_documents'] as $doc)
                    <li>{{ $doc }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endforeach
    </table>

    <p><em>Note: PEC employees are highlighted in yellow.</em></p>
    @else
    <p class="no-data">No missing documents found for any employees.</p>
    @endif

    <p>This is an automated report. Please contact HR if you have any questions.</p>
</body>

</html>