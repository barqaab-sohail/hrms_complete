<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
</head>

<body>
  <style>
    body,
    html {
      height: 100%;
      width: 100%;
      margin: 0;
      padding: 0;
      background: #fbfbfb !important;
    }

    .searchbar {
      margin-top: 50px;
      height: 60px;
      background-color: #353b48;
      border-radius: 30px;
      padding: 10px;
    }

    .search_input {
      color: white;
      border: 0;
      outline: 0;
      background: none;
      width: 300;
      caret-color: transparent;
      line-height: 40px;
      transition: width 0.4s linear;
    }

    .searchbar:hover>.search_input {
      padding: 0 10px;
      width: 300px;
      caret-color: red;
      transition: width 0.4s linear;
    }

    .searchbar:hover>.search_icon {
      background: white;
      color: #e74c3c;
    }

    .search_icon {
      height: 40px;
      width: 40px;
      float: right;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      color: white;
      text-decoration: none;
    }
  </style>
  <div class="container">
    <div class="d-flex justify-content-center">
      <div class="searchbar">
        <input class="search_input" id="search_input" type="text" name="" placeholder="Please Enter CNIC without dash" />
        <a href="#" class="search_icon"><i class="fas fa-search"></i></a>
      </div>
    </div>
  </div>
  <div>
    <div class="container">
      <div class="d-flex justify-content-center">
        <div>
          <br>
          <h3 id="name"></h3>
          <h3 id="position"></h3>
          <h3 id="status"></h3>
          <img id="image" width="150" />
        </div>
      </div>
    </div>


  </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //Enter Comma after three digit
    $("#search_input").keyup(function(ev) {
      let input = ev.target.value.split("-").join("");
      if (ev.target.value.length > 15) {
        input = input.substring(0, input.length - 1)
      }

      //Make a new string with the hyphens
      // Note that we make it into an array, and then join it at the end
      // This is so that we can use .map() 
      input = input.split('').map(function(cur, index) {

        //If the size of input is 6 or 8, insert dash before it
        //else, just insert input
        if (index == 5 || index == 12)
          return "-" + cur;
        else
          return cur;
      }).join('');

      //Return the new string
      $(this).val(input);

    });

    $('.search_icon').click(function() {
      var cnic = $("#search_input").val();
      $.ajax({
        url: "{{ url('verificationResult') }}" + '/' + cnic,
        method: "GET",
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          console.log(data);
          console.log(data.picture);
          if (data) {
            $('#name').text('Name: ' + data.full_name);
            $('#position').text('Designation: ' + data.designation);
            if (data.hr_status_id == 'Active') {
              $('#status').text('Current Status: Working');
            } else {
              $('#status').text('Current Status: Not Working');
            }
            var image = data.picture.path + data.picture.file_name;
            var imageurl = "{{asset('storage/:id')}}".replace(':id', image);
            $("#image").attr('src', imageurl);

          } else {
            $('#name').text('No Record Found');
            $('#position').text('');
            $('#status').text('');
            $("#image").removeAttr('src');
          }

          $('img').on('error', function() {
            this.src = 'https://i.imgur.com/CbsGGVp.jpg';
          });

        },
        error: function(jqXHR, textStatus, errorThrown) {



        } //end error
      }); //end ajax
    });
  });
</script>

</html>