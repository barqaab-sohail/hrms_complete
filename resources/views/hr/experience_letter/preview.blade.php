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
                        
                        <p>This is to certify that <strong>Mr. {{ $employee->full_name }}</strong>, s/o {{ $employee->father_name }}, holding
                        CNIC No. {{ $employee->cnic }}, has 
                        @if($is_current_employee)
                            been employed full-time with this organization since {{ $joining_date }}. 
                            He is currently serving as {{ $designation }} on "{{ $project }}" Project.
                        @else
                            worked with this organization as {{ $designation }} from {{ $joining_date }} till {{ $leaving_date }}, 
                            on "{{ $project }}" Project.
                        @endif
                        </p>
                        
                        <p>Throughout the tenure of employment, Mr. {{ $employee->last_name }} demonstrated a
                        strong work ethic, professionalism, and dedication to his responsibilities.</p>
                        
                        <p>This @if($is_current_employee)<strong>Experience Certificate</strong>@else certificate @endif 
                        is issued upon his own request.</p>
                        
                        <div style="margin-top: 50px; text-align: right; position: relative; width: 100%;">
                            <div style="display: inline-block; position: relative; text-align: left; width: auto; margin-right: 0;">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <form method="POST" action="{{ route('experience-letters.generate') }}" style="display: inline-block;">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="project" value="{{ $project }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-download"></i> Generate PDF with Letterhead
                    </button>
                </form>
                <form method="POST" action="{{ route('experience-letters.generate-without-letterhead') }}" style="display: inline-block;">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <input type="hidden" name="project" value="{{ $project }}">
                    <button type="submit" class="btn btn-info">
                        <i class="fa fa-download"></i> Generate PDF without Letterhead
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#previewModal').modal('show');
    });
</script>