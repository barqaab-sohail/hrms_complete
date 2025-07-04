<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Experience Letter for {{ $employee->first_name }} {{ $employee->last_name }}</title>
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
            text-align: justify; /* Added for text justification */
        }
        .page {
            position: relative;
            width: 210mm;
            min-height: 297mm;
            @if(!($show_letterhead ?? true)) 
                padding-top: 20mm; /* Add top padding when no letterhead */
            @endif
        }
        .letterhead {
            width: 100%;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
            @if(!($show_letterhead ?? true)) 
                display: none; /* Hide letterhead when flag is false */
            @endif
        }
        .content {
            padding: @if($show_letterhead ?? true) 30mm @else 0 @endif 20mm 20mm 20mm;
        }
        .address-block p {
            margin: 0;
            line-height: 1.2;
        }
        .signature-block {
            margin-top: 200px;
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
            top: -160px;
            z-index: 1;
        }
        .stamp-img {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: auto;
            top: 200px;
            z-index: 1;
        }
        .justified-text {
            text-align: justify;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        @if($show_letterhead ?? true)
            <img src="{{ $letterhead_url }}" class="letterhead" alt="BARQAAB Letterhead">
        @endif

        <div class="content">
            <p style="text-align: right;">Dated: {{ $date }}</p>
            
            <p style="text-align: center;"><strong>TO WHOM IT MAY CONCERN</strong></p>
            
            <!-- In the content section of the PDF view -->
            <p class="justified-text">This is to certify that <strong>Mr. {{ $employee->full_name }}</strong>, s/o {{ $employee->father_name }}, holding
            CNIC No. {{ $employee->cnic }}, has 
            @if($is_current_employee)
                been employed full-time with this organization since {{ $joining_date }}. 
                He is currently serving as {{ $designation }} on "{{ $project }}" Project.
            @else
                worked with this organization as {{ $designation }} from {{ $joining_date }} till {{ $leaving_date }}, 
                on "{{ $project }}" Project.
            @endif
            </p>
            
            <p class="justified-text">Throughout the tenure of employment, Mr. {{ $employee->full_name }} demonstrated a
            strong work ethic, professionalism, and dedication to his responsibilities.</p>
            
            <p>This <strong>Experience Certificate</strong> 
            is issued upon his own request.</p>
            
            <div class="signature-block">
                <div class="signature-wrapper">
                    @if($show_signature ?? true)
                        <img src="{{ $sign }}" class="signature-img" alt="Signature">
                    @endif
                    <div class="signatory-info">
                        <p><strong>({{ $signatory }})</strong></p>
                        <p>{{ $signatory_position }}</p>
                    </div>
                    @if($show_stamp ?? true)
                        <img src="{{ $stamp }}" class="stamp-img" alt="Stamp">
                    @endif
                </div>
              
                <div class="col-md-12" style="text-align:left; color:black; font-weight: bold;">
                    <br>
                    {!! '<img
                        src="data:image/png;base64,'. DNS2D::getBarcodePNG(url("/storage/$path"),'QRCODE',6,6). '"
                        class="profile-pic" alt="barcode" />' !!}
                    <br>
                    <p style="margin-top:0%; font-size:0.4rem;">For Content Verification</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>