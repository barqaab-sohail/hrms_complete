@if(Session::has('success'))
    <div class="alert alert-success">
       <a href="#" class="close" data-dismiss="alert">x</a>
          <strong>{{Session::get('success')}}</strong> 
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">x</a>
            @foreach ($errors->all() as $error)
                <strong> {{ $error }}</strong><br>
             @endforeach
     </div>
@endif

 @if (Session::has('message'))
        <div class="alert alert-success" align="left">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>{{Session::get('message')}}</strong> 
        </div>
 @endif

@if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
@endif