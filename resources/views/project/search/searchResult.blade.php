@if($result->count()!=0)

<hr>         
        <div class="card">
        <div class="card-body">
            <!--<div class="float-right">
                <input id="month" class="form-control" value="" type="month">
            </div>-->
            
            <div class="table-responsive m-t-40">
            
            <table id="myDataTable" class="table table-bordered table-striped" width="100%" cellspacing="0">
                <thead>
                
                    <tr>
                        <th>Description</th>
                        <th>Reference No.</th>
                        <th>Date</th>
                        <th>Size (MB)</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result as $documentId)
                    <tr>
                        <td>{{$documentId->description}}</td>
                        <td>{{$documentId->reference_no}}</td>
                        <td>{{$documentId->document_date}}</td>
                        <td>{{round(($documentId->size/1000000),2)}}</td>
                        @if(($documentId->extension == "jpg")||($documentId->extension == "jpeg")||($documentId->extension == "png"))
                        <td><img  id="ViewIMG" src="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" href="{{asset(isset($documentId->file_name)?  'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30/></td>
                        @elseif ($documentId->extension == 'pdf')
                        <td><img  id="ViewPDF" src="{{asset('Massets/images/document.png')}}" href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30/></td>
                        @else
                        <td><a href="{{asset(isset($documentId->file_name)? 'storage/'.$documentId->path.$documentId->file_name: 'Massets/images/document.png') }}" width=30><i class="fa fa-download" aria-hidden="true"></i>Download</a></td>
                        @endif
 
                    </tr>
                    @endforeach
                        
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <hr>  

<script>
$(document).ready(function() {
    $('#myDataTable').DataTable({
                    "order": [[ 2, "desc" ]],
                    stateSave: false,
                    dom: 'flrti',
                    scrollY:        "500px",
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         false,
                    fixedColumns:   {
                        leftColumns: 1,
                        rightColumns:2
                    }
        });

         //function view from list table
        $(function(){

             $('#ViewPDF, #ViewIMG').EZView();
        });



        $("form").submit(function (e) {
         e.preventDefault();
        });

        $('a[id^=editDocument]').click(function (e){
        e.preventDefault();
        
        var url = $(this).attr('href');
        getAjaxData(url);

      });


        $("form[id^=deleteDocument]").submit(function(e) { 
        e.preventDefault();
        var url = $(this).attr('action');
        $('.fa-spinner').show(); 
        var folderUrl = $('.fa-folder-open').closest('a').attr('href');
        refreshTable(folderUrl,1000);
        submitForm(this, url);
        resetForm();
             
        });

            
});
</script>
@endif