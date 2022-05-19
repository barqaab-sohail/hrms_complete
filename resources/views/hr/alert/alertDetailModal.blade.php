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

                <table id="alertDetail" class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead >
                    
                      <tr >
                          <th style="font-weight:bold">Employee Name</th>
                          <th id="project" style="font-weight:bold">Project/Office</th>
                          <th style="font-weight:bold">Expiry Date</th>
                          <th style="font-weight:bold">Mobile</th>
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


$('#cnicExpiryDetail').click(function(e){ 
  e.preventDefault(); 
  
  var url = $(this).attr('href');
     $.ajax({
     url:url,
     dataType:"json",
     success:function(data){
        $('#alert_name').text(data.full_name);
        $('#alertDetail > tbody').empty();
        // $('#project').remove();      
        $.each(data.cnicExpiry, function() {
          var office='';
          if (this.employee_project == 'overhead'){
            office = this.employee_office;
          }else{
             office = this.employee_project;
          }
          $('#alertDetail > tbody').append(
              '<tr><td>'
              + this.employee_name
              + '</td><td>'
              + office
              + '</td><td>'
              + this.cnic_expiry_date
              + '</td><td>'
              + this.mobile
              + '</td></tr>'
          );
        });
     }
    });
    $('#alertDetailModel').modal('show');
});

$('#appointmentExpiryDetail').click(function(e){ 
  e.preventDefault();

  var url = $(this).attr('href');
     $.ajax({
     url:url,
     dataType:"json",
    
     success:function(data){

        $('#alert_name').text(data.full_name);
        $('#alertDetail > tbody').empty();
        $.each(data.appointmentExpiry, function() {
          var office='';
          if (this.employee_project == 'overhead'){
            office = this.employee_office;
          }else{
             office = this.employee_project;
          }
          $('#alertDetail > tbody').append(
              '<tr><td>'
              + this.employee_name
              + '</td><td>'
              + office
              + '</td><td>'
              + this.appointment_expiry_date
              + '</td><td>'
              + this.mobile
              + '</td></tr>'
          );
        });
        $('#alertDetailModel').modal('show');
     }     
    });
});

$('#drivingLicenceExpiryTotal').click(function(e){ 
    e.preventDefault();
  var url = $(this).attr('href');
     $.ajax({
     url:url,
     dataType:"json",
     
     success:function(data){

        $('#alert_name').text(data.full_name);
        $('#alertDetail > tbody').empty();
        $.each(data.drivingLicenceExpiryTotal, function() {
          var office='';
          if (this.employee_project == 'overhead'){
            office = this.employee_office;
          }else{
             office = this.employee_project;
          }
         
          $('#alertDetail > tbody').append(
              '<tr><td>'
              + this.employee_name
              + '</td><td>'
              + office
              + '</td><td>'
              + this.licence_expiry_date
              + '</td><td>'
              + this.mobile
              + '</td></tr>'
          );
        });
      $('#alertDetailModel').modal('show');
     }
    });
 
});

$('#pecCardExpiry').click(function(e){ 
    e.preventDefault();
    var url = $(this).attr('href');
     $.ajax({
     url:url,
     dataType:"json",
     
     success:function(data){

        $('#alert_name').text(data.full_name);
        $('#alertDetail > tbody').empty(); 
        $.each(data.pecCardExpiry, function() {
          var office='';
          if (this.employee_project == 'overhead'){
            office = this.employee_office;
          }else{
             office = this.employee_project;
          }

          $('#alertDetail > tbody').append(
              '<tr><td>'
              + this.employee_name
              + '</td><td>'
              + office
              + '</td><td>'
              + this.pec_expiry_date
              + '</td><td>'
              + this.mobile
              + '</td></tr>'
          );
        });
      $('#alertDetailModel').modal('show');
     }
    });
});



</script>

<!--end Model--> 