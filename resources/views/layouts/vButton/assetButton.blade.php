<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
         
          <br>
          
            <a type="submit" role="button" id="addAsset" href="{{route('asset.edit',session('asset_id'))}}" class="btn btn-success" {{Request::is('hrms/asset/*/edit')?'style=background-color:#737373':''}}>Asset Detail</a>
            <a type="submit" role="button" id="addPurchase" href="{{route('asPurchase.edit',session('asset_id'))}}" class="btn btn-success" {Request::is('hrms/asset/asPurchase')?'style=background-color:#737373':''}}>Purchase Detail</a>
             <a type="submit" role="button" id="addOwnership" href="{{route('asOwnership.index',session('asset_id'))}}" class="btn btn-success" {Request::is('hrms/asOwnership')?'style=background-color:#737373':''}}>Ownership</a>
            <a type="submit" role="button" id="addLocation" href="{{route('asLocation.index',session('asset_id'))}}" class="btn btn-success" {Request::is('hrms/asLocation')?'style=background-color:#737373':''}}>Asset Location</a>
            <a type="submit" role="button" id="addDocument" href="{{route('asDocument.index')}}" class="btn btn-success" {Request::is('hrms/asset/asDocument')?'style=background-color:#737373':''}}>Documents</a>
            <a type="submit" role="button" id="addMaintenance" href="{{route('asMaintenance.index')}}"  class="btn btn-success" {Request::is('hrms/asset/asMaintenance')?'style=background-color:#737373':''}}>Maintenance</a>
            <a type="submit" role="button" id="addCondition" href="{{route('asCondition.index')}}"  class="btn btn-success" {Request::is('hrms/asset/asCondition')?'style=background-color:#737373':''}}>Condition</a>

            <br>
            
           
         <style>
            
        .btn-success:active { background-color: red; }
            
        </style>

          <br>



             
</div>