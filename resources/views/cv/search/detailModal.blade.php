<!-- Modal -->
<div class="modal fade" id="detailModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog  modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail of Expert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
          <div class="table-responsive">


                <h2> <span id="full_name"></span> </h2>
                <br>
                <table id="cvDetail" class="table-primary table-bordered table-striped" width="100%" cellspacing="0">
                    <thead >
                    
                      <tr >
                          <th>Speciality</th>
                          <th>Discipline</th>
                           <th>Stage</th>
                          <th>Experience</th>
                         
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

$('button[name=edit]').click(function(){ 
  var id = $(this).attr('id');
  var url = "{{route('cv.getData')}}"+"/"+id;

     $.ajax({
     url:url,
     dataType:"json",
     
     success:function(data){

        $('#full_name').text(data.full_name);
        $("#tbody").empty();
                
        $.each(data.cv_experience, function() {
          $('#cvDetail > tbody').append(
              '<tr><td>'
              + this.cv_specialization_id
              + '</td><td>'
              + this.cv_discipline_id
              + '</td><td>'
              + this.cv_stage_id
              + '</td><td class="text-center">'
              + this.year +
              '</td></tr>'
          );
        });

      
        $('#detailModel').modal('show');


     }
    })
  
});



</script>

<!--end Model--> 