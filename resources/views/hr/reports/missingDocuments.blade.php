text/x-generic missingDocuments.blade.php ( HTML document, UTF-8 Unicode text )
@extends('layouts.master.master')
@section('title', 'BARQAAB HR')
<h3 class="text-themecolor"></h3>
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Mandatory Missing Document List</h4>

        <div class="table-responsive m-t-40">


            <table class="table table-bordered data-table" width=100%>
                <thead>
                    <tr class="text-center">
                        <th class="text-left bg-primary text-white">Employee ID</th>
                        <th class="text-left bg-primary text-white">Employee Name</th>
                        <th class="text-left bg-primary text-white">Designation</th>
                        <th class="text-left bg-primary text-white">Division</th>
                        <th class="text-left bg-primary text-white">Project</th>
                        <th class="text-left bg-primary text-white">CNIC Front</th>
                        <th class="text-left bg-primary text-white">Picture</th>
                        <th class="text-left bg-primary text-white">Signed Appointment</th>
                        <th class="text-left bg-primary text-white">Appointment Letter</th>
                        <th class="text-left bg-primary text-white">HR Form</th>
                        <th class="text-left bg-primary text-white">Joining Report</th>
                        <th class="text-left bg-primary text-white">Engineering Degree</th>
                        <th class="text-left bg-primary text-white">Educational Documents</th>
                        <th class="text-left bg-primary text-white">Mobile</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $('.data-table').DataTable({

            ajax: {
                url: "{{ route('missingDocuments.list') }}",
            },
            columns: [{
                    data: "employee_no",
                    name: 'employee_no'
                },
                {
                    data: "full_name",
                    name: 'full_name'
                },
                {
                    data: "designation",
                    name: 'designation'
                },
                {
                    data: "division",
                    name: 'division'
                },

                {
                    data: "project",
                    name: 'project'
                },

                {
                    data: "front_cnic",
                    name: 'front_cnic'
                },

                {
                    data: "picture",
                    name: 'picture'
                },

                {
                    data: "signed_appointment_letter",
                    name: 'signed_appointment_letter'
                },

                {
                    data: "appointment_letter",
                    name: 'appointment_letter'
                },

                {
                    data: "Hr_Form",
                    name: 'Hr_Form'
                },

                {
                    data: "joining_report",
                    name: 'joining_report'
                },

                {
                    data: "engineer_degree",
                    name: 'engineer_degree'
                },


                {
                    data: "education_documents",
                    name: 'education_documents'
                },

                {
                    data: "mobile",
                    name: 'mobile'
                }

            ],
            processing: true,
            serverSide: true,
            fixedHeader: true,
            dom: 'Blfrtip',
            buttons: ['copy', 'excel', 'pdf'],
            scrollY: "300px",

            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],

            // columnDefs: [{
            //     targets: 4,
            //     render: function(data, type, row) {
            //         return type === 'display' && data.length > 30 ? data.substr(0, 30) + '…' :
            //             data;
            //     }
            // }],
        });
    });
</script>
@endpush