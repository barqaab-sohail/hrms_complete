<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Bank Letter Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display the letter content without letterhead for preview -->
                <div style="font-family: Arial, sans-serif; line-height: 1.5; padding: 20px;">
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
                                <td style="border: 1px solid #000; padding: 8px; text-align: center;">Mr. {{ $employee->full_name }}</td>
                                <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $employee->designation }}</td>
                                <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $employee->cnic }}</td>
                                <td style="border: 1px solid #000; padding: 8px; text-align: center;">Rs. {{ number_format($salary) }}/-</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p style="margin-top: 40px; text-align: right;">Regards</p>
                    <p style="margin-top: 40px; text-align: right;">
                        <strong>({{ $signatory }})</strong><br>
                        {{ $signatory_position }}
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="generateWithLetterhead">Generate PDF</button>
               
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#previewModal').modal('show');
        
        // Handle PDF generation with letterhead
        $('#generateWithLetterhead').click(function() {
            generatePdf('{{ route("bank-letters.generate") }}');
        });
        
                
        function generatePdf(url) {
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: '{{ $employee->id }}',
                    bank_id: '{{ $bank->id }}',
                    salary: '{{ $salary }}',
                },
                success: function(response) {
                    // Close the modal
                    $('#previewModal').modal('hide');
                    
                    // Trigger DataTable update in parent window
                    if (window.opener) {
                        window.opener.updateBankLettersTable();
                    } else {
                        window.parent.updateBankLettersTable();
                    }
                    
                    // Download the PDF if a download URL is provided
                    if (response.download_url) {
                        window.location.href = response.download_url;
                    }
                },
                error: function(xhr) {
                    alert('Error generating PDF: ' + xhr.responseJSON.message);
                }
            });
        }
    });
    </script>