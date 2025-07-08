<!-- resources/views/bank-letters/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bank Letter for {{ $employee->first_name }} {{ $employee->last_name }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .page {
            position: relative;
            width: 210mm;
            min-height: 297mm;
        }
        .letterhead {
            width: 100%;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .content {
            padding: 30mm 20mm 20mm 20mm;
        }
        /* Address formatting */
        .address-block p {
            margin: 0;
            line-height: 1.2;
        }
        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
       /* Signature section styling */
       .signature-block {
            margin-top: 50px;
            text-align: right;
            position: relative;
            width: 100%;
        }
        
        .signature-wrapper {
            display: inline-block;
            position: relative;
            text-align: left;
            width: auto;
            margin-right: 0;
        }
        
        .signatory-info {
            text-align: right;
            line-height: 0.5;
        }
        
        .signature-img {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: auto;
            top: -160px; /* 5% overlap */
            z-index: 1;
        }
        
        .stamp-img {
            position: absolute;
            left: 10%;
            transform: translateX(-50%);
            width: 300px;
            height: auto;
            top: -160px;
            z-index: 1;
        }

        .no-wrap {
        white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="page">
        <img src="{{ $letterhead_url }}" class="letterhead" alt="BARQAAB Letterhead">

        <div class="content">
            <p style="text-align: right;">Dated: {{ $date }}</p>
            
            <div class="address-block">
                <p>To,</p>
                <p>The Manager,</p>
                <p>{{ $bank->name }}</p>
            </div>
            
            <p><strong>Subject: TO WHOM IT MAY CONCERN</strong></p>
            
            <p>Respected Sir/Madam,</p>
            
            <p>It is to confirm that the following staff is an employee of BARQAAB Consulting Services;</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Sr. #</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>CNIC #</th>
                        <th>Gross Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td>Mr. {{ $employee->full_name }}</td>
                        <td>{{ $employee->designation }}</td>
                        <td class="no-wrap">{{ $employee->cnic }}</td>
                        <td>Rs. {{ $salary }}/-</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="signature-block">
                <p>Regards</p>
                <br>
                <br>
                <div class="signature-wrapper">
                    
                    <!-- Signature centered relative to name -->
                    <img src="{{ $sign }}" class="signature-img" alt="Signature">
                    <!-- Stamp centered relative to name -->
                    <img src="{{ $stamp }}" class="stamp-img" alt="Stamp">
                    
                    <!-- Name and position right-aligned -->
                    <div class="signatory-info">
                        <p><strong>({{ $signatory }})</strong></p>
                        <p>{{ $signatory_position }}</p>
                    </div>
                    
                    
                </div>
              
                <div class="col-md-12" style="text-align:left; color:black; font-weight: bold;">
                    <br>
                    <br>
                    <br>
                    <br>
                    {!! '<img
                        src="data:image/png;base64,'. DNS2D::getBarcodePNG(url("/storage/$path"),'QRCODE',7,7). '"
                        class="profile-pic" alt="barcode" />' !!}
                    <br>
                    <p style="margin: 0; font-size: 0.4rem; line-height: 1;">Scan for Content</p>
                    <p style="margin: 0; font-size: 0.4rem; line-height: 1;">Verification-{{auth()->user()->hrEmployee->employee_no }}</p>
                </div>
               
            </div>
        </div>
    </div>
</body>
</html>