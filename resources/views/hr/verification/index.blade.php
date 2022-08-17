<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
  <link rel="stylesheet" href="{{asset('verification/style.css')}}" />
</head>

<body>

  <div class="container">
    <div class="d-flex justify-content-center">
      <div class="searchbar">
        <input class="search_input" id="search_input" type="text" name="" autocomplete="off" placeholder="Please Enter CNIC without dash" />
        <a href="#" id="search" class="search_icon"><i class="fas fa-search"></i></a>
      </div>
    </div>
  </div>


  <div class='container-fluid'>
    <div class="card mx-auto col-md-12 col-10 mt-5">
      <img class='mx-auto img-thumbnail' width="150" height="auto" />
      <div class="card-body text-center mx-auto">
        <div class='cvp'>
          <h5 class="card-title font-weight-bold" id="emp_name"></h5>
          <h5 class="card-title font-weight-bold" id="emp_des"></h5>
          <h5 class="card-title font-weight-bold" id="emp_status"></h5>

        </div>
      </div>
    </div>

  </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="{{asset('verification\card.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.card').hide();
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

    // Get the input field
    var input = document.getElementById("search_input");
    // Execute a function when the user presses a key on the keyboard
    input.addEventListener("keypress", function(event) {
      // If the user presses the "Enter" key on the keyboard
      if (event.key === "Enter") {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("search").click();
      }
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
            $('.card').show();
            $(".img-thumbnail").show();
            $('#emp_name').text('Name: ' + data.full_name);
            $('#emp_des').html(' Designation: ' + data.designation);
            if (data.hr_status_id == 'Active') {
              $('#emp_status').text('Current Status: Working');
            } else {
              $('#emp_status').text('Current Status: Not Working');
            }

            if (data.picture) {
              var image = data.picture.path + data.picture.file_name;
              var imageurl = "{{asset('storage/:id')}}".replace(':id', image);
              $(".img-thumbnail").attr('src', imageurl);
            }

            $('img').on('error', function() {
              this.src = "{{asset('Massets/images/default.png')}}";
            });

          } else {
            $('#emp_name').text('No Record Found');
            $('#emp_des').text('');
            $('#emp_status').text('');
            $(".img-thumbnail").hide();
          }



        },
        error: function(jqXHR, textStatus, errorThrown) {



        } //end error
      }); //end ajax
    });
  });
</script>

</html>