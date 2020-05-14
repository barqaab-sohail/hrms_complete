<!-- Modal -->
<div class="modal fade" id="detailModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog  modal-xl" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail of Expert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
          <div class="table-responsive">


                <label class="control-label text-right">Full Name</label><br>
                <table id="myTable" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                    
                      <tr >
                          <th>Speciality</th>
                          <th>Discipline</th>
                           <th>Stage</th>
                          <th>Experience</th>
                         
                      </tr>
                    </thead>
                    <tbody>
                        <th>Construction Supervision Engineer</th>
                        <th>Infrastructure (Roads & Buildings)</th>
                        <th>Construction</th>
                        <th>10</th>
                   
                    </tbody>
                </table>
 
      </div>
      
    </div>
  </div>
</div>



<script>


//$(document).on('click', 'button[id^=detail]',function(){ 

$('button[name=edit]').click(function(){ 
  var id = $(this).attr('id');
  console.log(id);
    $('#detailModel').modal('show');
    // $.ajax({
    //  url:"/ajax-crud/"+id+"/edit",
    //  dataType:"json",
     
    //  success:function(html){
    //   $('#first_name').val(html.data.first_name);
    //   $('#last_name').val(html.data.last_name);
    //   $('#store_image').html("<img src={{ URL::to('/') }}/images/" + html.data.image + " width='70' class='img-thumbnail' />");
    //   $('#store_image').append("<input type='hidden' name='hidden_image' value='"+html.data.image+"' />");
    //   $('#hidden_id').val(html.data.id);
    //   $('.modal-title').text("Edit New Record");
    //   $('#action_button').val("Edit");
    //   $('#action').val("Edit");
    //   $('#detailModel').modal('show');
    //  }
    // })
  
});



</script>

<!--end Model--> 