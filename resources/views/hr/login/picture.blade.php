@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
@section('Heading')
    <h3 class="text-themecolor">Human Resource</h3>
    
        <h4>{{'Employee Name: '}} {{ucwords($data->first_name)}} {{ ucwords($data->last_name)}}</h4>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card card-outline-info">
            
                <div class="row">
                    <div class="col-lg-2">
                    @include('layouts.vButton.hrButton')
                    </div>
            
                    <div class="col-lg-10">
                         
 

                        <div style="margin-top:10px; margin-right: 10px;">
                            
                        </div>
                         
 

                            <p>&nbsp;</p>
<div  class="container"> 
        
    
          <div class="form-group">
            @csrf
            <div class="row">
              <div class="col-md-4">
                <div id="image-preview"></div>
              </div>
              <div class="col-md-4" style="padding:75px; border-right:1px solid #ddd;">
                <p><label>Select Image</label></p>
                <input type="file" name="upload_image" id="upload_image" />
                <br />
                <br />
                <button class="btn btn-success crop_image">Crop & Upload Image</button>
              </div>
              <div class="col-md-4" style="padding:75px;background-color: #333">
                <div id="uploaded_image" align="center"></div>
              </div>
            </div>
         
        <br />
        <br />  
        <br />
        <br /> 

</div>   
                        

                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
 $(document).ready(function(){
  
  $image_crop = $('#image-preview').croppie({
    enableExif:true,
    viewport:{
      width:290,
      height:290,
      type:'square'
    },
    boundary:{
      width:300,
      height:300
    }
  });

  $('#upload_image').change(function(){
    var reader = new FileReader();

    reader.onload = function(event){
      $image_crop.croppie('bind', {
        url:event.target.result
      }).then(function(){
      });
    }
    reader.readAsDataURL(this.files[0]);
  });

  $('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type:'square',
      size:'viewport',
    }).then(function(response){
      var _token = $('input[name=_token]').val();
      $.ajax({
        url:'{{ route("picture.store") }}',
        type:'post',
        data:{"image":response, _token:_token},
        dataType:"json",
        success:function(data)
        {
          var crop_image = '<img src="'+data.path+'" />';
          $('#uploaded_image').html(crop_image);
        }
      });
    });
  });
  
});  

</script>



@stop