<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
         
          <br>
          
            
            <a type="submit" role="button" id="addSubmission" href="{{route('submission.edit',session('submission_id'))}}" class="btn btn-info" {{Request::is('hrms/submission/*/edit')?'style=background-color:#737373':''}}>Submission Detail</a>
            
            <a type="submit" role="button" id="addDocument" href="{{route('submissionDocument.create')}}" class="btn btn-info" {Request::is('hrms/submissionDocument/create')?'style=background-color:#737373':''}}>Documents</a>
            
            <br>
            

         <style>
            
        .btn-info:active { background-color: red; }
            
        </style>

          <br>



             
</div>