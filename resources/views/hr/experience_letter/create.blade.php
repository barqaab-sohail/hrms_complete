@extends('layouts.master.master')
@section('title', 'Experience Letter')
@section('Heading')
<h3 class="text-themecolor">Experience Letter</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        <h2>Generate Experience Letter</h2>
        
        <form id="experienceLetterForm" method="POST" action="{{ route('experience-letters.preview') }}">
            @csrf
            
            <div class="form-group">
                <label for="employee_id">Employee</label>
                <select class="form-control select2" id="employee_id" name="employee_id" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                            data-status="{{ $employee->hr_status_id }}"
                            data-project="{{ $employee->project ?? '' }}">
                            {{ $employee->full_name }} - {{ $employee->designation }}
                            ({{ $employee->hr_status_id == 'Active' ? 'Active' : 'Previous' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="project">Project</label>
                <input type="text" class="form-control" id="project" name="project" 
                       placeholder="Enter project name or leave empty to use employee's default">
                <small class="form-text text-muted">
                    Leave blank to use employee's current project
                </small>
            </div>
            
            <button type="submit" class="btn btn-primary">Preview Letter</button>
        </form>
        
        <!-- Modal Container -->
        <div id="modalContainer"></div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true
    });
    
    // Update project field when employee changes
    $('#employee_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        $('#project').val(selectedOption.data('project'));
    });
    
    $('#experienceLetterForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#modalContainer').html(response);
                $('#previewModal').modal('show');
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    $(document).on('hidden.bs.modal', '#previewModal', function() {
        $('#modalContainer').empty();
    });
});
</script>
@stop