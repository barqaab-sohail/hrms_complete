@extends('layouts.master.master')
@section('title', 'Bank Accounts Letter')
@section('Heading')
<h3 class="text-themecolor">Bank Accounts Letter</h3>
@stop
@section('content')
<div class="card">
    <div class="card-body">
        
            <h2>Generate Bank Opening Letter</h2>
            
            <form id="bankLetterForm" method="POST" action="{{ route('bank-letters.preview') }}">
                @csrf
                
                <div class="form-group">
                    <label for="employee_id">Employee</label>
                    <select class="form-control select2" id="employee_id" name="employee_id" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->full_name }}  - {{ $employee->designation}}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="bank_id">Bank</label>
                    <select class="form-control select2" id="bank_id" name="bank_id" required>
                        <option value="">Select Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="salary">Salary (PKR)</label>
                    <input type="number" class="form-control" id="salary" name="salary" 
                           placeholder="Leave empty to use employee's default salary">
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
    $('#bankLetterForm').on('submit', function(e) {
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
    
    // Handle modal close
    $(document).on('hidden.bs.modal', '#previewModal', function() {
        $('#modalContainer').empty();
    });
});
</script>

        
@stop