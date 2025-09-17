<style>
    .spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    vertical-align: text-bottom;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border .75s linear infinite;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}

.d-none {
    display: none !important;
}
</style>
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    Experience Letter Preview - {{ $employee->full_name }}
                    ({{ $is_current_employee ? 'Current' : 'Previous' }} Employee)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="page">
                     <div style="padding: 30mm 20mm 20mm 20mm;">
                        <p style="text-align: right;">Dated: {{ $date }}</p>
                        
                        <p><strong>TO WHOM IT MAY CONCERN</strong></p>
                        
                        <!-- Custom or predefined content -->
                        @if($content_type === 'custom' && !empty($custom_content))
                            {!! $custom_content !!}
                        @else
                            <p>This is to certify that <strong>Mr. {{ $employee->full_name }}</strong>, s/o {{ $employee->father_name }}, holding
                            CNIC No. {{ $employee->cnic }}, has 
                            @if($is_current_employee)
                                been employed full-time with this organization since {{ $joining_date }}. 
                                He is currently serving as {{ $designation }} on {{ $project }}.
                            @else
                                worked with this organization as {{ $designation }} from {{ $joining_date }} till {{ $leaving_date }}, 
                                on {{ $project }}.
                            @endif
                            </p>
                            
                            <p>Throughout the tenure of employment, Mr. {{ $employee->last_name }} demonstrated a
                            strong work ethic, professionalism, and dedication to his responsibilities.</p>
                            
                            <p>This @if($is_current_employee)<strong>Experience Certificate</strong>@else<strong>Experience Certificate</strong>@endif 
                            is issued upon his own request.</p>
                        @endif
                        
                        <div style="margin-top: 200px; text-align: right; position: relative; width: 100%;">
                            <div style="display: inline-block; position: relative; text-align: left; width: auto; margin-right: 0;">
                                {{-- <img src="{{ asset('letterhead/sign.png') }}" style="position: absolute; left: 50%; transform: translateX(-50%); width: 100px; height: auto; top: -160px; z-index: 1;" alt="Signature">
                                <img src="{{ asset('letterhead/stamp.png') }}" style="position: absolute; left: 10%; transform: translateX(-50%); width: 100px; height: auto; top: -160px; z-index: 1;" alt="Stamp"> --}}
                                <div style="text-align: right; line-height: 0.5;">
                                    <p><strong>({{ $signatory }})</strong></p>
                                    <p>{{ $signatory_position }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form id="generateForm" method="POST" action="{{ route('experience-letters.generate') }}">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="project" value="{{ $project }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="joining_date" value="{{ $joining_date }}">
                    <input type="hidden" name="leaving_date" value="{{ $leaving_date }}">
                    <input type="hidden" name="content_type" value="{{ $content_type }}">
                    <input type="hidden" name="custom_content" value="{{ $custom_content }}">
                    
                    <button type="submit" class="btn btn-primary" id="generateBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Generate PDF with Letter Head
                    </button>
                </form>
                
                <form id="generateWithoutLetterheadForm" method="POST" action="{{ route('experience-letters.generate-without-letterhead') }}">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="project" value="{{ $project }}">
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="joining_date" value="{{ $joining_date }}">
                    <input type="hidden" name="leaving_date" value="{{ $leaving_date }}">
                    <input type="hidden" name="content_type" value="{{ $content_type }}">
                    <input type="hidden" name="custom_content" value="{{ $custom_content }}">
                    
                    <button type="submit" class="btn btn-secondary" id="generateWithoutBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Generate PDF without Letter Head
                    </button>
                </form>
                
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#generateForm, #generateWithoutLetterheadForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = form.find('button[type="submit"]');
            btn.prop('disabled', true);
            btn.find('.spinner-border').removeClass('d-none');
            
            // Submit form via AJAX
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                xhrFields: {
                    responseType: 'blob' // Important for file download
                },
                success: function(data, status, xhr) {
                    // Create a blob from the response
                    var blob = new Blob([data], {type: 'application/pdf'});
                    
                    // Create a link element
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    
                    // Get filename from content-disposition header
                    var contentDisposition = xhr.getResponseHeader('content-disposition');
                    var filename = 'experience_letter.pdf';
                    if (contentDisposition) {
                        var filenameMatch = contentDisposition.match(/filename="?(.+)"?/);
                        if (filenameMatch.length === 2) {
                            filename = filenameMatch[1];
                        }
                    }
                    
                    link.download = filename;
                    
                    // Append link to body, click and remove
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Re-enable button and hide spinner
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').addClass('d-none');
                    
                    // Close modal
                    $('#previewModal').modal('hide');
                    
                    // Trigger DataTable update in parent window
                    if (window.opener) {
                        window.opener.updateExperienceLettersTable();
                    } else {
                        window.parent.updateExperienceLettersTable();
                    }
                },
                error: function(xhr) {
                    // Re-enable button and hide spinner on error
                    btn.prop('disabled', false);
                    btn.find('.spinner-border').addClass('d-none');
                    
                    alert('Error generating PDF: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>