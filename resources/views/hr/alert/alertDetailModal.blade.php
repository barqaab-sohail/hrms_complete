<!-- Modal -->
<div class="modal fade" id="alertDetailModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog  modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="alert_name">Detail of Alert</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
          <div class="table-responsive">

                <table id="alertDetail" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
                    <thead >
                    
                      <tr >
                          <th>Employee Name</th>
                          <th id="project">Project</th>
                          <th>Expiry Date</th>
                      </tr>
                    </thead>
                    <tbody id="tbody">
                       
                   
                    </tbody>
                </table>
 
      </div>
      
    </div>
  </div>
</div>



<script>


//$(document).on('click', 'button[id^=detail]',function(){ 

$('#cnicExpiryDetail').click(function(){ 
  var url = $(this).attr('href');
     $.ajax({
     url:url,
     dataType:"json",
     
     success:function(data){

        $('#alert_name').text(data.full_name);
        $("#tbody").empty();
        $('#project').remove();
                
        $.each(data.cnicExpiry, function() {
          $('#alertDetail > tbody').append(
              '<tr><td>'
              + this.employee_name
              + '</td><td>'
              + this.cnic_expiry_date
              + '</td></tr>'
          );
        });

      
        $('#alertDetailModel').modal('show');


     }
    })

    
});

$('#appointmentExpiryDetail').click(function(){ 
  var url = $(this).attr('href');
  console.log(url);
     $.ajax({
     url:url,
     dataType:"json",
     
     success:function(data){

        $('#alert_name').text(data.full_name);
        $("#tbody").empty();
                
        $.each(data.appointmentExpiry, function() {
          $('#alertDetail > tbody').append(
              '<tr><td>'
              + this.employee_name
              + '</td><td>'
              + this.employee_project
              + '</td><td>'
              + this.appointment_expiry_date
              + '</td></tr>'
          );
        });

      
        $('#alertDetailModel').modal('show');


     }
    })
});




</script>

<!--end Model--> 