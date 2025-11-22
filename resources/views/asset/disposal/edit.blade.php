<div style="margin-top:10px; margin-right: 10px;">
    <button type="button" onclick="window.location.href='{{route('asset.index')}}'" class="btn btn-success float-right" data-toggle="tooltip" title="Back to List">List of Asset</button>
</div>
     
<div class="card-body">
    <form id="formDisposal" method="post" action="{{route('asDisposal.update',$data->id)}}" class="form-horizontal form-prevent-multiple-submits" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
        <div class="form-body">
                
            <h3 class="box-title">Asset Disposal Record</h3>
            
            <hr class="m-t-0 m-b-40">

            <div class="row">
                <!-- Asset Information -->
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="control-label text-right">Asset Code</label>
                            <input type="text" value="{{ $data->asset_code }}" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label text-right">Description</label>
                            <input type="text" value="{{ $data->description }}" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="control-label text-right">Current Status</label>
                            <input type="text" id="current_status" value="{{ $data->is_active ? 'Active' : 'Sold/Inactive' }}" class="form-control {{ $data->is_active ? 'text-success' : 'text-danger' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <!-- Sold Date -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Sold Date<span class="text_requried">*</span></label>
                            <input type="text" id="sold_date" name="sold_date" value="{{ old('sold_date', $data->disposal->sold_date ?? '') }}" class="form-control date_input" data-validation="required" readonly>
                            <br>
                            @can('hr edit record')<i class="fas fa-trash-alt text_requried"></i>@endcan 
                        </div>
                    </div>
                </div>

                <!-- Sold Price -->
                <div class="col-md-2">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Sold Price</label>
                            <input type="text" name="sold_price" id="sold_price" value="{{ old('sold_price', $data->disposal->sold_price ?? '') }}" class="form-control" placeholder="Enter Sold Price">
                        </div>
                    </div>
                </div>

                <!-- Sold To -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Sold To</label>
                            <input type="text" name="sold_to" value="{{ old('sold_to', $data->disposal->sold_to ?? '') }}" class="form-control" placeholder="Enter buyer name">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reason -->
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Reason for Disposal<span class="text_requried">*</span></label>
                            <textarea name="reason" rows="3" class="form-control" data-validation="required" placeholder="Enter reason for disposal">{{ old('reason', $data->disposal->reason ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="control-label text-right">Additional Notes</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Enter any additional notes">{{ old('notes', $data->disposal->notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!--/End Form Body-->

        <hr>

        <div class="form-actions">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-success btn-prevent-multiple-submits">
                                <i class="fa fa-spinner fa-spin" style="font-size:18px"></i>
                                {{ $data->disposal ? 'Update' : 'Mark as Sold' }}
                            </button>

                            @if($data->disposal)
                                <button type="button" onclick="deleteDisposal({{ $data->id }})" class="btn btn-danger btn-prevent-multiple-submits">
                                    <i class="fa fa-trash"></i> Remove Disposal
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div> <!-- end card body -->
<script>
$(document).ready(function() {
    // All Basic Form Implementation i.e validation etc.
    formFunctions();

    $('#formDisposal').on('submit', function(event){
        event.preventDefault(); // Prevent default form submission
        
        // Remove commas from sold_price before submitting
        var soldPrice = $('#sold_price').val();
        if (soldPrice) {
            $('#sold_price').val(soldPrice.replace(/,/g, ''));
        }
        
        var url = $(this).attr('action');
        $('.fa-spinner').show();
        submitFormAjax(this, url, 1); // Use 1 for no redirect, just show message
    }); // end submit

    // Price formatting
    $('#sold_price').keyup(function(event) {
        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        });
    });

    //ajax function
    function submitFormAjax(form, url, reset=0){
        //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
        $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
            var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
            }
        });
        
        // Remove commas from sold_price before submitting
        var soldPrice = $('#sold_price').val();
        if (soldPrice) {
            $('#sold_price').val(soldPrice.replace(/,/g, ''));
        }
        var data = new FormData(form)

       // ajax request
        $.ajax({
           url:url,
           method:"POST",
           data:data,
           //dataType:'JSON',
           contentType: false,
           cache: false,
           processData: false,
           success:function(data)
               {
                if(reset == 1){
                    $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                    
                    // Update UI without refresh
                    updateUIAfterSubmit();
                    $('html,body').scrollTop(0);
                    $('.fa-spinner').hide();
                    clearMessage();
                }else{
                    location.href = data.url;
                }           
                
               },
            error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status == 401){
                    location.href = "{{route ('login')}}"
                    }      

                    var test = jqXHR.responseJSON // this object have two more objects one is errors and other is message.
                    
                    var errorMassage = '';

                    //now saperate only errors object values from test object and store in variable errorMassage;
                    $.each(test.errors, function (key, value){
                      errorMassage += value + '<br>';
                    });
                     
                      $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMassage+'</strong></div>');
                    $('html,body').scrollTop(0);
                    $('.fa-spinner').hide();
                                  
                }//end error
        }); //end ajax
    }

    // Function to update UI after form submission (Mark as Sold/Update)
    function updateUIAfterSubmit() {
        // Change button text
        $('button[type="submit"]').html('<i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Update');
        
        // Update current status using specific ID
        $('#current_status').val('Sold/Inactive').removeClass('text-success').addClass('text-danger');

        // Show the "Remove Disposal" button if it doesn't exist
        if ($('button[onclick*="deleteDisposal"]').length === 0) {
            $('button[type="submit"]').after(
                '<button type="button" onclick="deleteDisposal({{ $data->id }})" class="btn btn-danger btn-prevent-multiple-submits ml-2">' +
                '<i class="fa fa-trash"></i> Remove Disposal' +
                '</button>'
            );
        }
    }

    // Function to update UI after delete (Remove Disposal)
    function updateUIAfterDelete() {
        // Change button text from "Update" to "Mark as Sold"
        $('button[type="submit"]').html('<i class="fa fa-spinner fa-spin" style="font-size:18px"></i>Mark as Sold');
        
        // Update current status using specific ID
        $('#current_status').val('Active').removeClass('text-danger').addClass('text-success');

        // Clear form fields
        $('#sold_date').val('');
        $('#sold_price').val('');
        $('input[name="sold_to"]').val('');
        $('textarea[name="reason"]').val('');
        $('textarea[name="notes"]').val('');

        // Remove the "Remove Disposal" button
        $('button[onclick*="deleteDisposal"]').remove();
    }

    // Make functions available globally
    window.updateUIAfterSubmit = updateUIAfterSubmit;
    window.updateUIAfterDelete = updateUIAfterDelete;

});

function deleteDisposal(assetId) {
    if (confirm('Are you sure you want to remove this disposal record? This will mark the asset as active again.')) {
        $.ajax({
            url: "{{ url('hrms/asset') }}/" + assetId + "/disposal",
            method: "DELETE",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "DELETE"
            },
            success: function(data) {
                $('#json_message').html('<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+data.message+'</strong></div>');
                
                // Call the correct function to update UI
                if (typeof updateUIAfterDelete === 'function') {
                    updateUIAfterDelete();
                    $('.fa-spinner').hide();
                    
                }
                $('html,body').scrollTop(0);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 401) {
                    location.href = "{{route ('login')}}";
                } else {
                    var errorMessage = 'Error removing disposal record';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }
                    $('#json_message').html('<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>'+errorMessage+'</strong></div>');
                }
                $('html,body').scrollTop(0);
            }
        });
    }
}

// Make the function global
window.deleteDisposal = deleteDisposal;
</script>
