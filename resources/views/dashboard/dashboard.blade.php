@extends('layouts.master.master')
@section('title', 'BARQAAB HR')


@section('Heading')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css" />



<h3 class="text-themecolor">Dashboard</h3>
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"></a></li>


</ol>

@stop
@section('content')

<div class="card">
    <div class="card-body">
        <!--<div class="float-right">
				<input id="month" class="form-control" value="" type="month">
			</div>
			<h4 class="card-title">Salaries</h4>
			-->

        <h2>{{ucwords (Auth::User()->hrEmployee->first_name??'')}} {{ucwords(Auth::User()->hrEmployee->last_name??'')}}
            Welcome to HRMS</h2>



        <!-- <div class="container" id='hideDiv'>
        <h3 align="center">Import Excel File</h3>

      <form id= "fromImportSalary" method="post" enctype="multipart/form-data" action="{{route('employeeSalaryImport')}}">
      {{ csrf_field() }}
          <div class="form-group">
            <table class="table">
              <tr>
                <td width="40%" align="right"><label>Select File for Upload</label></td>
                  <td width="30">
                  <input type="file" name="select_file" />
                  </td>
                  <td width="30%" align="left">
                  <input type="submit" name="upload" class="btn btn-success" value="Upload">
                  </td>
              </tr>
              <tr>
                  <td width="40%" align="right"></td>
                  <td width="30"><span class="text-muted">.xls, .xslx Files Only</span></td>
                  <td width="30%" align="left"></td>
              </tr>
            </table>
          </div>
      </form>
    </div> -->
        @can('tasks')
        <!--TASK -->
        <div class="card">
            <div class="card-body">

                <!-- Toggle table button -->
                <button type="button" id="toggleTableBtn" class="btn btn-primary">
                    Show Table
                </button>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#taskModal">
                    Add New Task
                </button>

                <!-- Modeal Include-->
                @include('self.task.modal')
                @include('self.task.editModal')

                <div id="append_data" class="table-responsive m-t-40 table-container" style="display: none;">
                </div>
            </div>
        </div>
        <!--End TASK -->
        @endcan

        <hr>


    </div>
</div>

@can('hr view graph')


<div class="row">
    <div class="col-sm-6">
        <div id="ageChart" style="height: 500px;"></div>
    </div>
    <div class="col-sm-6">
        <div id="engineerChart" style="height: 500px;"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div id="categoryChart" style="height: 500px;"></div>
    </div>
    <div class="col-sm-6">
        <div id="departmentChart" style="height: 500px;"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div id="educationChart" style="height: 500px;"></div>
    </div>
</div>



@include('hr.charts.category')
@include('hr.charts.ageChart')
@include('hr.charts.engineerChart')
@include('hr.charts.departmentChart')
@include('hr.charts.educationChart')
@endcan




@stop

@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggleTableBtn');
        const tableContainer = document.getElementById('append_data');
        
        toggleBtn.addEventListener('click', function() {
            if (tableContainer.style.display === 'none') {
                tableContainer.style.display = 'block';
                toggleBtn.textContent = 'Hide Table';
            } else {
                tableContainer.style.display = 'none';
                toggleBtn.textContent = 'Show Table';
            }
        });
    });

    $(document).ready(function() {
      
        //var url = "{{route('task.index')}}";
        //refreshTable("{{route('task.index')}}");
        //load_data();
        $('#fromImportSalary').on('submit', function(event) {
            //preventDefault work through formFunctions;
            url = "{{route('employeeSalaryImport')}}";
            $('.fa-spinner').show();
            submitFormAjax(this, url);
        }); //en

        $('#myDataTable').DataTable({
            stateSave: false,
            dom: 'flrti',
            scrollY: "500px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 2
            }
        });
    });


    function load_data() {
        var loadUrl = "{{route('task.index')}}";
        $("#append_data").load(loadUrl, function() {
            $('#myTable').DataTable({
                stateSave: false,
                "order": [
                    [2, "asc"]
                ],
                "destroy": true,
                "columnDefs": [{
                        "width": "30%",
                        "targets": 0,
                    },
                    {
                        "targets": "_all",
                        "className": "dt-center"
                    }
                ],
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                ]

            });
        });
    }
</script>
@endpush

@section('footer')

@stop