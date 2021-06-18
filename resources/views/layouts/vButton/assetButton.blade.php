<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
         
          <br>
          
            <a type="submit" role="button" id="addAsset" href="{{route('asset.edit',session('asset_id'))}}" class="btn btn-info" {{Request::is('hrms/asset/*/edit')?'style=background-color:#737373':''}}>Asset Detail</a>
            <a type="submit" role="button" id="addDocument" href="" class="btn btn-info" {Request::is('hrms/cvData/cvDocument/create')?'style=background-color:#737373':''}}>Documents</a>
          
            
            <br>
            
           
         <style>
            
        .btn-info:active { background-color: red; }
            
        </style>

          <br>



             
</div>