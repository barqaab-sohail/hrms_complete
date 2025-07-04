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
                    
                    <p style="margin-top: 40px;">Regards</p>
                    <p style="margin-top: 40px;">
                        <strong>({{ $signatory }})</strong><br>
                        {{ $signatory_position }}
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form method="POST" action="{{ route('bank-letters.generate') }}">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="bank_id" value="{{ $bank->id }}">
                    <input type="hidden" name="salary" value="{{ $salary }}">
                    <button type="submit" class="btn btn-primary">Generate PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>