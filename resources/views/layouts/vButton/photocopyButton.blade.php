<div class="dropdownSubMenue">
  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span id="dropdownSubMenueText" style="font-size:20px"></span>
  </button>


  <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">
    <br>
    <a type="submit" role="button" id="addPhotocopyRecord" style="color:white" href="{{route('photocopy_record.show',$photocopy->id)}}" class="btn btn-success  dropdown-item" {{Request::is('/hrms/photocopy_record/*')?'style=background-color:#737373':''}}>Records</a>
    <a type="submit" role="button" id="addDocuments" style="color:white" href="{{route('photocopy_documents.show',$photocopy->id)}}" class="btn btn-success  dropdown-item">Documents</a>
    <br>

    <style>
      .btn-success:active {
        background-color: red;
      }
    </style>

    <br>




  </div>
</div>