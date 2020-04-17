<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
         
          <br>
          
          

            <a type="submit" role="button" href="{{route('cv.edit',session('cv_detail_id'))}}" class="btn btn-info" {{Request::is('hrms/cv/*/edit')?'style=background-color:#737373':''}} >CV Detail</a>
            <a type="submit" role="button"  href="{{route('cv.create')}}" class="btn btn-info @if(request()->is('*cvDocument*')) active @endif">Documents</a>
          
            
            <br>
            
           
         <style>
            
        .btn-info:active { background-color: red; }
            
        </style>

          <br>



             
</div>