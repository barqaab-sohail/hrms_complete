<table>
    <thead>
        <tr>
            <th>Employee No</th>
            <th>Employee Name</th>
            <th>Joining Date</th>
            <th>Joining Report Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mismatches as $item)
        <tr>
            <td>{{ $item['employee_no'] }}</td>
            <td>{{ $item['employee_name'] }}</td>
            <td>{{ $item['joining_date'] }}</td>
            <td>{{ $item['joining_report_date'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
