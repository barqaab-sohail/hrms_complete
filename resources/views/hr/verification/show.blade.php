<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('Massets/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{asset('verification/style.css')}}" />
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center">
            <img src="{{asset('Massets/images/EmailMono.jpg')}}" />
            <br />
        </div>
        <div class="d-flex justify-content-center">
            <h1>BARQAAB Employee Card Verification</h1>
        </div>
    </div>



    <div class='container-fluid'>
        <div class="card mx-auto col-md-12 col-10 mt-5">
            <img class='mx-auto img-thumbnail' src="{{asset('storage/'.$data->picture->path.$data->picture->file_name)}}" width="150" height="auto" />
            <div class="card-body text-center mx-auto">
                <div class='cvp'>
                    <h5 class="card-title font-weight-bold" id="emp_name">Name: {{$data->full_name??''}}</h5>
                    <h5 class="card-title font-weight-bold" id="emp_des">Designation: {{$data->designation??''}}</h5>
                    <h5 class="card-title font-weight-bold" id="emp_status">{{$data->hr_status_id == 'Active'?'Current Status: Working':'Current Status: Not Working'}}</h5>

                </div>
            </div>
        </div>

    </div>
</body>

</html>