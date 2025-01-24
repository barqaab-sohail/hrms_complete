@extends('layouts.master.master')
@section('title', 'Last Login Detail')
@section('Heading')
<!-- <h3 class="text-themecolor">List of Employees</h3> -->
@stop
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="color:black">List of Last Login Detail</h4>
        <div class="table-responsive m-t-40">
            <table id="myTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Email</th>
                        <th>CNIC</th>
                        <th>Father Name</th>
                        <th>Location</th>
                        <th>Last Login Date</th>
                        <th>Last Login IP</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@include('submission.client.clientModal')
<script>
    $(document).ready(function() {
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                "aaSorting": [],
                ajax: {
                    url: "{{ route('lastLogin.detail') }}",
                },
                columns: [{
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'cnic',
                        name: 'cnic'
                    },
                    {
                        data: 'father_name',
                        name: 'father_name'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'last_login_at',
                        name: 'last_login_at'
                    },
                    {
                        data: 'last_login_ip',
                        name: 'last_login_ip'
                    },

                ]
            });

        }); // end function

    }); //End document ready function
</script>

</div>

@stop