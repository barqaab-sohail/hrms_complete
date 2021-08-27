<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
         
          <br>
          
          

            <a type="submit" role="button" id="addCv" href="{{route('cv.edit',session('cv_detail_id'))}}" class="btn btn-success" {{Request::is('hrms/cvData/cv/*/edit')?'style=background-color:#737373':''}}>CV Detail</a>
            <a type="submit" role="button" id="addDocument" href="{{route('cvDocument.create')}}" class="btn btn-success" {Request::is('hrms/cvData/cvDocument/create')?'style=background-color:#737373':''}}>Documents</a>
          
            
            <br>
            
           
         <style>
            
        .btn-success:active { background-color: red; }
            
        </style>

          <br>



             
</div>