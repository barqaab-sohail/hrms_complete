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
<style>
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0;
    /* <-- Apparently some margin are still there even though it's hidden */
  }

  input[type=number] {
    -moz-appearance: textfield;
    /* Firefox */
  }
</style>

<body>
  <div class="container">
    <div class="d-flex justify-content-center">
      <img src="{{asset('Massets/images/EmailMono.jpg')}}" />
      <br />
    </div>
    <div class="d-flex justify-content-center">
      <h1>BARQAAB Employee Verification</h1>
    </div>
  </div>

  <div class="container">
    <div class="d-flex justify-content-center">
      <div class="searchbar">
        <label style="color:white;">CNIC</label>
        <input class="search_input cnic" id="search_input_cnic" type="text" name="" autocomplete="off" />
        <a href="#" id="search" class="search_icon"><i class="fas fa-search"></i></a>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="d-flex justify-content-center">
      <div class="searchbar">
        <label style="color:white;">Employee No</label>
        <input class="search_input employee_no" id="search_input_employee_no" type="text" name="" maxlength="7" autocomplete="off" />
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
          <h5 class="card-title font-weight-bold" id="emp_expiry"></h5>

        </div>
      </div>
    </div>

  </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="{{asset('verification\card.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#search_input_cnic").keyup(function() {
      $("#search_input_employee_no").val('');
    });
    $("#search_input_employee_no").keyup(function() {
      $("#search_input_cnic").val('');
    });

    $('#search_input_employee_no, #search_input_cnic').keypress(function(e) {

      var charCode = (e.which) ? e.which : event.keyCode

      if (String.fromCharCode(charCode).match(/[^0-9]/g))

        return false;

    });


    var cnic = document.getElementById('search_input_cnic'),
      cleanPhoneNumber;

    cleanPhoneNumber = function(e) {
      e.preventDefault();
      var pastedText = '';
      if (window.clipboardData && window.clipboardData.getData) { // IE
        pastedText = window.clipboardData.getData('Text');
      } else if (e.clipboardData && e.clipboardData.getData) {
        pastedText = e.clipboardData.getData('text/plain');
      }
      this.value = pastedText.replace(/\D/g, '');
    };

    cnic.onpaste = cleanPhoneNumber;



    $(document).on('keydown', '#search_input_cnic, #search_input_employee_no', function(e) {
      if (e.keyCode == 32) return false;
    });

    $('.card').hide();
    //Enter Comma after three digit
    $("#search_input_cnic").keyup(function(ev) {
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
    var input = document.getElementById("search_input_cnic");
    var inputEmployee = document.getElementById("search_input_employee_no");

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

    // Execute a function when the user presses a key on the keyboard
    inputEmployee.addEventListener("keypress", function(event) {
      // If the user presses the "Enter" key on the keyboard
      if (event.key === "Enter") {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("search").click();
      }
    });

    function dateInDayMonthYear(date) {
      //get Date from Database and set as "Saturday, 24-August-2019"
      var weekday = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

      var months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      //if Date not empty than enter date with format 'Wednesday, 10-August-2010'
      var Date1 = new Date(date);
      return (
        weekday[Date1.getDay()] +
        ", " +
        Date1.getDate() +
        "-" +
        months[Date1.getMonth()] +
        "-" +
        Date1.getFullYear()
      );
    }

    $('.search_icon').click(function() {
      var cnic = $("#search_input_cnic").val();
      var employeeNo = $("#search_input_employee_no").val();


      if (!(cnic || employeeNo)) {

        alert('Please Enter CNIC or Employee No');
        return;
      } else if (cnic.length < 15 && employeeNo == '') {
        alert('CNIC is not Complete');
        return;
      } else if (cnic == '' && employeeNo.length < 7) {
        alert('Employee No is not Complete');
        return;
      }
      var inputData = null;
      if (cnic) {
        inputData = cnic;
      } else {
        inputData = employeeNo;
      }

      $.ajax({
        url: "{{ url('verificationResult') }}" + '/' + inputData,
        method: "GET",
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          console.log(data.data);
          if (data.data) {
            $('.card').show();
            $(".img-thumbnail").show();
            $('#emp_name').text('Name: ' + data.data.first_name + ' ' + data.data.last_name);
            $('#emp_des').html(' Designation: ' + data.data.employee_current_designation.name);
            if (data.data.hr_status_id == 'Active') {
              $('#emp_status').text('Current Status: Working');
            } else {
              $('#emp_status').text('Current Status: Not Working');
            }
            if (data.data.employee_appointment?.expiry_date == null) {
              $('#emp_expiry').html(' Contract Expiry:  Not Applicable');
            } else {
              $('#emp_expiry').html(' Contract Expiry: ' + dateInDayMonthYear(data.data.employee_appointment?.expiry_date));
            }

            if (data.data.picture) {
              var image = data.data.picture.path + data.data.picture.file_name;
              var imageurl = "{{asset('storage/:id')}}".replace(':id', image);
              $(".img-thumbnail").attr('src', imageurl);
            } else {
              $(".img-thumbnail").attr('src', "{{asset('Massets/images/default.png')}}");
            }

            $('img').on('error', function() {
              this.src = "{{asset('Massets/images/default.png')}}";
            });

          } else {
            $('#emp_name').text(data.error);
            $('#emp_des').text('');
            $('#emp_status').text('');
            $('#emp_expiry').text('');
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