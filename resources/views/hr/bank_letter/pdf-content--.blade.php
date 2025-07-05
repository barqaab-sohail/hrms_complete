<div style="font-family: Arial, sans-serif; line-height: 1.5;">
    <!-- Letterhead -->
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 24px;">BARQAAB</h1>
        <h2 style="margin: 0; font-size: 18px;">Consulting Services (Pvt.) Limited</h2>
        <hr style="border-top: 1px solid #000; margin: 10px 0;">
        
        <div style="font-size: 12px; text-align: center;">
            <strong>HEAD OFFICE:</strong><br>
            Sunny View Estate, Kashmir Road, Lahore<br>
            Phone: (042) 99202093-94, 99203384 & 99200063<br>
            Fax: (042) 99202095<br>
            E-mail: info@bargaab.com, Web: www.bargaab.com
        </div>
    </div>
    
    <!-- Letter content -->
    <div style="margin-top: 50px;">
        <p style="text-align: right;">Dated: {{ $date }}</p>
        
        <p>To,</p>
        <p>The Manager,</p>
        <p>{{ $bank->name }}</p>
        
        <p style="margin-top: 20px;"><strong>Subject: TO WHOM IT MAY CONCERN</strong></p>
        
        <p style="margin-top: 20px;">Respected Sir/Madam,</p>
        
        <p style="margin-top: 20px;">
            It is to confirm that the following staff is an employee of BARQAAB Consulting Services;
        </p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Sr. #</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Name</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Position</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">CNIC #</th>
                    <th style="border: 1px solid #000; padding: 8px; text-align: center;">Gross Salary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">1.</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Mr. {{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $employee->position }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $employee->cnic }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">Rs. {{ number_format($salary) }}/-</td>
                </tr>
            </tbody>
        </table>
        
        <p style="margin-top: 40px;">Regards</p>
        <p style="margin-top: 40px;">
            <strong>({{ $signatory }})</strong><br>
            {{ $signatory_position }}
        </p>
    </div>
</div>