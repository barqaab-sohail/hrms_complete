<div class="dropdownSubMenue">
  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span id="dropdownSubMenueText" style="font-size:20px"></span>
  </button>


  <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">   
          <br>       
            <a type="submit" role="button" id="addAsset" style="color:white" href="{{route('asset.edit',session('asset_id'))}}" class="btn btn-success  dropdown-item" {{Request::is('hrms/asset/*/edit')?'style=background-color:#737373':''}}>Asset Detail</a>
            <a type="submit" role="button" id="addPurchase" style="color:white" href="{{route('asPurchase.edit',session('asset_id'))}}" class="btn btn-success  dropdown-item" {Request::is('hrms/asset/asPurchase')?'style=background-color:#737373':''}}>Purchase Detail</a>
             <a type="submit" role="button" id="addOwnership" style="color:white" href="{{route('asOwnership.index',session('asset_id'))}}" class="btn btn-success  dropdown-item" {Request::is('hrms/asOwnership')?'style=background-color:#737373':''}}>Ownership</a>
            <a type="submit" role="button" id="addLocation" style="color:white" href="{{route('asLocation.index',session('asset_id'))}}" class="btn btn-success  dropdown-item" {Request::is('hrms/asLocation')?'style=background-color:#737373':''}}>Asset Location</a>
            <a type="submit" role="button" id="addDocument" style="color:white" href="{{route('asDocument.index')}}" class="btn btn-success  dropdown-item" {Request::is('hrms/asset/asDocument')?'style=background-color:#737373':''}}>Documents</a>
            <a type="submit" role="button" id="addMaintenance" style="color:white" href="{{route('asMaintenance.index')}}"  class="btn btn-success  dropdown-item" {Request::is('hrms/asset/asMaintenance')?'style=background-color:#737373':''}}>Maintenance</a>
            <a type="submit" role="button" id="addCondition" style="color:white" href="{{route('asCondition.index')}}"  class="btn btn-success  dropdown-item" {Request::is('hrms/asset/asCondition')?'style=background-color:#737373':''}}>Condition</a>
            <br>
            
           
         <style>
            
        .btn-success:active { background-color: red; }
            
        </style>

          <br>



             
  </div>
</div>