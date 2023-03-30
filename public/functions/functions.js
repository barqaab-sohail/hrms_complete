function refreshTable(url, time = 50) {
    $("div.table-container").fadeOut();
    setTimeout(function () {
        $("div.table-container").load(url, function () {
            $("div.table-container").fadeIn();
        });
    }, time);
}

function fromPreventDefault() {
    $("form").submit(function (e) {
        e.preventDefault();
    });
}

function isUserData(id, url) {
    if (id.indexOf(url) >= 0) {
        $('input[type="text"]').prop("readonly", true);
        $("select").each(function () {
            $("option").each(function () {
                if (!this.selected) {
                    $(this).remove();
                }
            });
        });
        $("button").remove();
    } //end if
}

function selectTwo(parameter = ".selectTwo") {
    $(parameter).select2({
        width: "100%",
        theme: "classic",

        errorPlacement: function (error, element) {
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent()); // radio/checkbox?
            } else if (element.hasClass("select2")) {
                error.insertAfter(element.next("span")); // select2
            } else {
                error.insertAfter(element); // default
            }
        },
    });
}
function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function (match) {
        return match.toUpperCase();
    });
}

function dateInMonthYear(date) {
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
    return months[Date1.getMonth()] + " " + Date1.getFullYear();
}

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

function formFunctions() {
    $(".hideDiv").hide();

    //get Date from Database and set as "Saturday, 24-August-2019"
    var weekday = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];

    //if Date not empty than enter date with format 'Wednesday, 10-August-2010'

    $(".date_input, .date_input1").each(function () {
        if ($(this).val() != "") {
            var Date1 = new Date($(this).val());
            $(this).val(
                weekday[Date1.getDay()] +
                    ", " +
                    Date1.getDate() +
                    "-" +
                    months[Date1.getMonth()] +
                    "-" +
                    Date1.getFullYear()
            );
        } else {
            $(this).siblings("i").hide();
        }
    });

    $(".date_input").each(function () {
        if ($(this).val() != "") {
            var Date1 = new Date($(this).val());
            $(this).val(
                weekday[Date1.getDay()] +
                    ", " +
                    Date1.getDate() +
                    "-" +
                    months[Date1.getMonth()] +
                    "-" +
                    Date1.getFullYear()
            );
        } else {
            $(this).siblings("i").hide();
        }
    });

    //double click submission prevent

    (function () {
        $(".form-prevent-multiple-submits").on("submit", function () {
            $(".btn-prevent-multiple-submits").attr("disabled", "ture");
            $(".spinner", this).show();
            //submit enalbe after 3 second
            setTimeout(function () {
                $(".btn-prevent-multiple-submits").removeAttr("disabled");
            }, 3000);
        });
    })();

    //If Click icon than clear date
    $(".date_input, .date-picker")
        .siblings("i")
        .click(function () {
            if (confirm("Are you sure to clear date")) {
                $(this).siblings("input").val("");
                $(this).hide();
                $(this).siblings("span").text("");
            }
        });

    //If Click icon than clear time
    $(".time_input")
        .siblings("i")
        .click(function () {
            if (confirm("Are you sure to clear time")) {
                $(this).siblings("input").val("");
                $(this).hide();
                $(this).siblings("span").text("");
            }
        });

    // DatePicker
    $(".date_input").datepicker({
        onSelect: function (value, ui) {
            $(this).siblings("i").show();
            var today = new Date(),
                age = today.getFullYear() - ui.selectedYear;
            $("#age").text(age + " years total age");
        },
        dateFormat: "D, d-M-yy",
        yearRange: "1935:" + (new Date().getFullYear() + 15),
        changeMonth: true,
        changeYear: true,
    });

    // TimePicker
    $(".time_input").timepicker({
        change: function () {
            $(this).siblings("i").show();
        },
        timeFormat: "HH:mm",
    });

    $(".time_input").each(function () {
        if ($(this).val() == "") {
            $(this).siblings("i").hide();
        } else {
            $(this).siblings("i").show();
        }
    });

    //Title Case of all inputs type text and remove extra spaces
    // $("input[type=text]:not('.exempted, .notCapital')").keyup(function() {
    //     var result = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
    //     $(this).val(result);
    // }).blur(function() {
    //     var input = this.value.replace(/\s+/g, " ").trim();
    //     $(this).val(input);
    // });

    //Title Case of all inputs type text and remove extra spaces
    $("input.titleCase[type=text]")
        .keyup(function () {
            var result = this.value
                .toLowerCase()
                .replace(/\b\w/g, (l) => l.toUpperCase());
            $(this).val(result);
        })
        .blur(function () {
            var input = this.value.replace(/\s+/g, " ").trim();
            $(this).val(input);
        });

    //Lower Case of all inputs type email
    $("input[type=email]").keyup(function () {
        $(this).val($(this).val().toLowerCase());
    });

    $("input[type=number]").on("wheel", function (e) {
        return false;
    });

    selectTwo();
    fromPreventDefault();

    $.validate({
        validateHiddenInputs: true,
        validateOnEvent: true,
    });

    $(".fa-spinner").hide();
} //;end formFunctions

$(document).on("keypress", "#mobile", function (e) {
    //$("#mobile").keypress(function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message

        return false;
    }
});

//CNIC Format
//Make sure that the event fires on input change
$(document).on("input", "#cnic", function (ev) {
    // $("#cnic").on('input', function(ev){

    //Prevent default
    ev.preventDefault();

    //Remove hyphens
    let input = ev.target.value.split("-").join("");
    if (ev.target.value.length > 15) {
        input = input.substring(0, input.length - 1);
    }

    //Make a new string with the hyphens
    // Note that we make it into an array, and then join it at the end
    // This is so that we can use .map()
    input = input
        .split("")
        .map(function (cur, index) {
            //If the size of input is 6 or 8, insert dash before it
            //else, just insert input
            if (index == 5 || index == 12) return "-" + cur;
            else return cur;
        })
        .join("");

    //Return the new string
    $(this).val(input);
});

//Mobile format
//Make sure that the event fires on input change
$(document).on("input", "#mobile", function (ev) {
    // $("#cnic").on('input', function(ev){

    //Prevent default
    ev.preventDefault();

    //Remove hyphens
    let input = ev.target.value.split("-").join("");
    if (ev.target.value.length > 15) {
        input = input.substring(0, input.length - 1);
    }

    //Make a new string with the hyphens
    // Note that we make it into an array, and then join it at the end
    // This is so that we can use .map()
    input = input
        .split("")
        .map(function (cur, index) {
            //If the size of input is 6 or 8, insert dash before it
            //else, just insert input
            if (index == 4) return "-" + cur;
            else return cur;
        })
        .join("");

    //Return the new string
    $(this).val(input);
});

function resetForm() {
    $(":input", "form")
        .not(":button, :submit, :reset")
        .val("")
        .removeAttr("checked")
        .removeAttr("selected");

    $("select").val("").trigger("chosen:updated");
    $(".selectTwo").val("").select2("val", "All");
    $(".remove").click();
    $(".date_input").siblings("i").hide();
    $(".time_input").siblings("i").hide();
    $("input").removeClass("valid");
    $("input").removeClass("error");
    $("input[style='border-color: rgb(185, 74, 72);']")
        .css("border-color", "")
        .siblings("span")
        .attr("class", "help-block")
        .remove();
    $("iframe").remove();
    //$('#wizardPicturePreview').attr('src',"{{asset('Massets/images/document.png')}}").fadeIn('slow').attr('width','100%');
}

// HR get data through ajax
function getAjaxData(url) {
    $.ajax({
        url: url,
        method: "GET",
        //dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            $(".addAjax").html(data);
            formFunctions();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 401) {
                location.href = "{{route ('login')}}";
            }
        }, //end error
    }); //end ajax
}

// json message clear after 5 second
function clearMessage(time = 5000) {
    setTimeout(function () {
        $("#json_message").empty();
    }, time);
}

//load tasks
function load_data(url) {
    var loadUrl = url;
    $("#append_data").load(loadUrl, function () {
        $("#myTable").DataTable({
            stateSave: false,
            order: [[3, "asc"]],
            destroy: true,
            columnDefs: [
                { width: "30%", targets: 0 },
                { targets: "_all", className: "dt-center" },
            ],
            dom: "Blfrtip",
            buttons: [
                {
                    extend: "copyHtml5",
                    exportOptions: {
                        columns: [0, 1, 2],
                    },
                },
                {
                    extend: "excelHtml5",
                    exportOptions: {
                        columns: [0, 1, 2],
                    },
                },
            ],
        });
    });
}

// HR get data through ajax
function getAjaxMessage(url) {
    $.ajax({
        url: url,
        method: "GET",
        //dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            if (data.status == "OK") {
                $("#json_message").html(
                    '<div id="json_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                        data.message +
                        "</strong></div>"
                );
            } else {
                $("#json_message").html(
                    '<div id="json_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                        data.message +
                        "</strong></div>"
                );
            }
            clearMessage();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 401) {
                location.href = "{{route ('login')}}";
            }
        }, //end error
    }); //end ajax
}

function submitForm(form, url, reset = 0, callback) {
    //refresh token on each ajax request if this code not added than sendcond time ajax request on same page show earr token mismatched
    $.ajaxPrefilter(function (options, originalOptions, xhr) {
        // this will run before each request
        var token = $('meta[name="csrf-token"]').attr("content"); // or _token, whichever you are using

        if (token) {
            return xhr.setRequestHeader("X-CSRF-TOKEN", token); // adds directly to the XmlHttpRequest Object
        }
    });
    var data = new FormData(form);
    // ajax request
    $.ajax({
        url: url,
        method: "POST",
        data: data,
        //dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            if (data.status == "OK") {
                $("#json_message").html(
                    '<div id="j_message" class="alert alert-success" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                        data.message +
                        "</strong></div>"
                );
            } else {
                $("#json_message").html(
                    '<div id="j_message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                        data.message +
                        "</strong></div>"
                );
            }
            if (reset == 1) {
                resetForm();
            }
            $("html,body").scrollTop(0);
            $(".fa-spinner").hide();
            clearMessage();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 401) {
                location.href = "{{route ('login')}}";
            }
            var test = jqXHR.responseJSON; // this object have two more objects one is errors and other is message.

            var errorMassage = "";

            //now saperate only errors object values from test object and store in variable errorMassage;
            $.each(test.errors, function (key, value) {
                errorMassage += value + "<br>";
            });
            $("#json_message").html(
                '<div id="message" class="alert alert-danger" align="left"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>' +
                    errorMassage +
                    "</strong></div>"
            );
            $("html,body").scrollTop(0);
            $(".fa-spinner").hide();
        }, //end error
    }); //end ajax

    if (typeof callback === "function") {
        callback();
    }
}
