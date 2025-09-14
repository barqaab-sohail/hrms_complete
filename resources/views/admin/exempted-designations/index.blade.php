@extends('layouts.master.master')
@section('title', 'Exempted Designations for Documents')
@section('Heading')
<h3 class="text-themecolor">Exempted Designations for Documents</h3>
@stop
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Exempted Designations Manager</h4>
        <h6 class="card-subtitle mb-3">Manage designations exempt from education document requirements</h6>
       
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.exempted-designations.update') }}" id="designationsForm">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Current Exempted Designations</h5>
                <button type="button" class="btn btn-success btn-sm" id="addDesignationBtn">
                    <i class="fas fa-plus-circle"></i> Add New
                </button>
            </div>
            
            <div class="designation-list mb-3 border rounded p-3 bg-light" id="designationList">
                @foreach($designations as $index => $designation)
                    <div class="designation-item d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                        <input type="text" name="designations[]" value="{{ $designation }}" 
                               class="form-control form-control-sm" required>
                        <button type="button" class="btn btn-danger btn-sm remove-btn ms-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
        
        <div class="mt-4">
            <h5>How to Use</h5>
            <div class="card">
                <div class="card-body">
                    <ul class="mb-0 pl-3">
                        <li>Add new designations using the "Add New" button</li>
                        <li>Edit existing designations directly in the text fields</li>
                        <li>Remove designations using the trash icon</li>
                        <li>Click "Save Changes" to update the exempted designations file</li>
                        <li>The <code>examptEducationDocuments()</code> function will automatically use the updated list</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
$(document).ready(function() {
    // Add new designation
   
   $('#addDesignationBtn').click(function() {
        const newItem = $('<div class="designation-item d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">' +
        '<input type="text" name="designations[]" class="form-control form-control-sm" placeholder="Enter designation" required>' +
        '<button type="button" class="btn btn-danger btn-sm remove-btn ms-2">' +
        '<i class="fas fa-trash"></i>' +
        '</button>' +
        '</div>');
    
        // Append at the beginning instead of the end
        $('#designationList').prepend(newItem);
        
        // Add event listener to the new remove button
        newItem.find('.remove-btn').click(function() {
            $(this).closest('.designation-item').remove();
        });
        
        // Focus on the new input field for better UX
        newItem.find('input').focus();
    });
    
    // Remove designation
    $(document).on('click', '.remove-btn', function() {
        $(this).closest('.designation-item').remove();
    });
    
    // Form validation
    $('#designationsForm').submit(function() {
        let valid = true;
        $('input[name="designations[]"]').each(function() {
            if (!$(this).val().trim()) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!valid) {
            alert('Please fill in all designation fields or remove empty ones.');
            return false;
        }
        
        return true;
    });
});
</script>
@stop