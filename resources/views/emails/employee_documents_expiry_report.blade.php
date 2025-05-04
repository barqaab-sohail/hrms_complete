<!DOCTYPE html>
<html>

<head>
    <title>Weekly Employee Documents Expiry Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
        }

        h2 {
            color: #555;
            margin-top: 20px;
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

        .no-data {
            color: #777;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1>Weekly Employee Documents Expiry Report</h1>
    <p>Report generated on: {{ $reportData['reportDate'] }}</p>

    @if(!empty($reportData['appointmentExpiry']))
    <h2>Appointment Letters Expiring Soon (within 10 days)</h2>
    <table>
        <tr>
            <th>Employee Name</th>
            <th>Project</th>
            <th>Office</th>
            <th>Expiry Date</th>
            <th>Mobile</th>
        </tr>
        @foreach($reportData['appointmentExpiry'] as $item)
        <tr>
            <td>{{ $item['employee_name'] }}</td>
            <td>{{ $item['employee_project'] }}</td>
            <td>{{ $item['employee_office'] }}</td>
            <td>{{ $item['expiry_date'] }}</td>
            <td>{{ $item['mobile'] }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <h2>No Appointment Letters Expiring Soon</h2>
    <p class="no-data">No appointment letters are expiring in the next 10 days.</p>
    @endif

    @if(!empty($reportData['drivingLicenceExpiry']))
    <h2>Driving Licences Expiring Soon (within 10 days)</h2>
    <table>
        <tr>
            <th>Employee Name</th>
            <th>Project</th>
            <th>Office</th>
            <th>Expiry Date</th>
            <th>Mobile</th>
        </tr>
        @foreach($reportData['drivingLicenceExpiry'] as $item)
        <tr>
            <td>{{ $item['employee_name'] }}</td>
            <td>{{ $item['employee_project'] }}</td>
            <td>{{ $item['employee_office'] }}</td>
            <td>{{ $item['expiry_date'] }}</td>
            <td>{{ $item['mobile'] }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <h2>No Driving Licences Expiring Soon</h2>
    <p class="no-data">No driving licences are expiring in the next 10 days.</p>
    @endif

    @if(!empty($reportData['pecCardExpiry']))
    <h2>PEC Cards Expiring Soon (within 10 days)</h2>
    <table>
        <tr>
            <th>Employee Name</th>
            <th>Project</th>
            <th>Office</th>
            <th>PEC No.</th>
            <th>Expiry Date</th>
            <th>Mobile</th>
        </tr>
        @foreach($reportData['pecCardExpiry'] as $item)
        <tr>
            <td>{{ $item['employee_name'] }}</td>
            <td>{{ $item['employee_project'] }}</td>
            <td>{{ $item['employee_office'] }}</td>
            <td>{{ $item['pec'] }}</td>
            <td>{{ $item['expiry_date'] }}</td>
            <td>{{ $item['mobile'] }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <h2>No PEC Cards Expiring Soon</h2>
    <p class="no-data">No PEC cards are expiring in the next 10 days.</p>
    @endif



    <p>This is an automated report. Please contact IT if you have any questions.</p>
</body>

</html>