<!-- All Jquery -->
<!-- ============================================================== -->

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('Massets/plugins/popper/popper.min.js') }}"></script>
<script src="{{asset('Massets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('Massets/js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{asset('Massets/js/waves.js') }}"></script>
<!--Menu sidebar Dropdown Effect-->
<script src="{{asset('Massets/js/sidebarmenu.js') }}"></script>
<!--stickey kit -->
<script src="{{asset('Massets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{asset('Massets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!--Custom JavaScript in HRMS -->
<script src="{{asset('Massets/js/custom.js') }}"></script>

<!-- JS Form Validations -->
<script src="{{asset('Massets/js/js-validation/jquery.form-validator.min.js') }}"></script>

<!-- js functions -->
<script src="{{asset('functions/functions.js') }}"></script>





<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
<!--c3 JavaScript -->
<script src="{{asset('Massets/plugins/d3/d3.min.js') }}"></script>
<script src="{{asset('Massets/plugins/c3-master/c3.min.js') }}"></script>


<!--CDN ChartJS 
 -->
<script src="http://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>


<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{asset('Massets/plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>

<script src="{{asset('Massets/select2/select2.full.min.js')}}"></script>
<!-- This is data table -->
<script src="{{asset('Massets/plugins/datatables/datatables.min.js')}}"></script>
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<!-- end - This is for export functionality only -->
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


<script src="{{asset('Massets/plugins/html5-editor/wysihtml5-0.3.0.js')}}"></script>
<script src="{{asset('Massets/plugins/html5-editor/bootstrap-wysihtml5.js')}}"></script>

<!-- Jquery UI Datepicker "Redmond theme" JS -->
<script src="{{asset('Massets/js/jquery-ui/jquery-ui.js')}}"></script>

<script src="{{asset('Massets/js/full-image/EZView.js') }}"></script>

<script src="{{asset('Massets/js/crop/jquery.imgareaselect.min.js') }}"></script>
<script src="{{asset('Massets/js/crop/croppie.min.js') }}"></script>

<!-- Duplicate rows Merge -->
<script src="{{asset('Massets/js/rowsmerge/jquery.rowspanizer.min.js') }}"></script>


<!-- chosen plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.js"></script>


<!-- Datatable Date formate(dd-mmm-yyyy) Sotring -->
<script src="https://cdn.datatables.net/plug-ins/1.10.25/sorting/date-dd-MMM-yyyy.js"></script>


<script src="https://cdn.tiny.cloud/1/6k6kj2mbbmwv1jqeh7sqe7jf29uemxfwvq4kzdpz5a4j9gm1/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea',
    forced_root_block: "",

  });
</script>

<!-- Google Chart -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
  //Email validation on ajax call
  function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
  }
</script>

<!-- Sub Menue Button -->
<script type="text/javascript">
  $(document).ready(function() {
    $('.dropdown-item').on('click', function() {
      $('#dropdownSubMenueText').text($(this).text());
    });

    var showText = $('.dropdownSubMenue').find('a:first').text();
    $('#dropdownSubMenueText').text(showText);
    $(".dropdownSubMenue").hover(function() {
      $('.reducedCol').removeClass('col-lg-12').addClass('col-lg-10');
      $(this).addClass('dropdown');
      var dropdownMenu = $(this).children(".dropdown-menu");
      if (dropdownMenu.is(":visible")) {
        dropdownMenu.parent().toggleClass("open");
        $('.dropdown-item').click(function() {
          $(".dropdown").removeClass("dropdown");
        });
      } else {
        $('.reducedCol').removeClass('col-lg-10').addClass('col-lg-12');
      }
    });

  });
</script>