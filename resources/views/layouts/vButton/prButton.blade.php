<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
         
          <br>
          
            
            <a type="submit" role="button" id="addProject" href="{{route('project.edit',session('pr_detail_id'))}}" class="btn btn-info" {{Request::is('hrms/project/*/edit')?'style=background-color:#737373':''}}>Project Detail</a>
            <a type="submit" role="button" id="addDocument" href="{{route('projectDocument.create')}}" class="btn btn-info" {Request::is('hrms/projectDocument/create')?'style=background-color:#737373':''}}>Documents</a>
            
            
            @can('Super Admin')
            @if($data->pr_role_id!=1)
            <a type="submit" role="button" id="addPosition" href="{{route('projectPartner.create')}}" class="btn btn-info" {Request::is('hrms/projectPartner/create')?'style=background-color:#737373':''}}>Partner Detail</a>
            @endif
            <a type="submit" role="button" id="addPosition" href="{{route('projectPosition.create')}}" class="btn btn-info" {Request::is('hrms/projectPosition/create')?'style=background-color:#737373':''}}>Position</a>
            @endcan
        
            <br>
            
           
         <style>
            
        .btn-info:active { background-color: red; }
            
        </style>

          <br>



             
</div>