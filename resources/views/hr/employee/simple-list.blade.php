@extends('layouts.master.master')
@section('title', 'Employees List')
@section('Heading')
<h3 class="text-themecolor">Employees List</h3>
@stop
@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="color:black">Employees List</h4>
        
        <div class="table-responsive m-t-40">
            <table id="employeesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employee Code</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>CNIC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Employee Summary Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Employee Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div id="employeeImage" class="mb-3">
                            <!-- Employee picture will be loaded here -->
                            <img src="" alt="Employee Photo" class="img-fluid rounded" id="empPhoto" style="max-height: 200px; display: none;">
                            <div id="noPhoto" class="text-muted">No photo available</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 id="empName" class="text-primary"></h5>
                                <p><strong>Employee Code:</strong> <span id="empCode"></span></p>
                                <p><strong>Father Name:</strong> <span id="empFatherName"></span></p>
                                <p><strong>CNIC:</strong> <span id="empCnic"></span></p>
                                <p><strong>Date of Birth:</strong> <span id="empDob"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Designation:</strong> <span id="empDesignation"></span></p>
                                <p><strong>Project:</strong> <span id="empProject"></span></p>
                                <p><strong>Office:</strong> <span id="empOffice"></span></p>
                                <p><strong>Mobile:</strong> <span id="empMobile"></span></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Gender:</strong> <span id="empGender"></span></p>
                                <p><strong>Marital Status:</strong> <span id="empMaritalStatus"></span></p>
                                <p><strong>Religion:</strong> <span id="empReligion"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Blood Group:</strong> <span id="empBloodGroup"></span></p>
                                <p><strong>Joining Date:</strong> <span id="empJoiningDate"></span></p>
                                <p><strong>Current Salary:</strong> <span id="empSalary"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-body p {
        margin-bottom: 8px;
    }
    #employeeImage {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #empPhoto {
        max-height: 200px;
        max-width: 100%;
    }
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#employeesTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('employee.simpleData') }}",
        columns: [
            { data: 'employee_no', name: 'employee_no' },
            { data: 'full_name', name: 'full_name' },
            { data: 'father_name', name: 'father_name' },
            { data: 'cnic', name: 'cnic' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        dom: 'Blfrtip',
        buttons: [
            'copy', 'excel', 'pdf'
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
    });

    // Handle view employee button click
    $(document).on('click', '.view-employee', function() {
        var employeeId = $(this).data('id');
        loadEmployeeSummary(employeeId);
    });

    function loadEmployeeSummary(employeeId) {
        $.ajax({
            url: "{{ url('hrms/hr/employee/summary') }}/" + employeeId,
            type: 'GET',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                // Update modal content
                $('#empName').text(response.first_name + ' ' + response.last_name);
                $('#empCode').text(response.employee_no);
                $('#empFatherName').text(response.father_name || 'N/A');
                $('#empCnic').text(response.cnic || 'N/A');
                $('#empDob').text(response.date_of_birth ? formatDate(response.date_of_birth) : 'N/A');
                $('#empDesignation').text(response.employee_current_designation?.name || 'N/A');
                $('#empProject').text(response.employee_current_project?.name || 'N/A');
                $('#empOffice').text(response.employee_current_office?.name || 'N/A');
                $('#empMobile').text(response.hr_contact_mobile?.mobile || 'N/A');
                $('#empGender').text(response.gender?.name || 'N/A');
                $('#empMaritalStatus').text(response.marital_status?.name || 'N/A');
                $('#empReligion').text(response.religion?.name || 'N/A');
                $('#empBloodGroup').text(response.hr_blood_group?.name || 'N/A');
                $('#empJoiningDate').text(response.employee_appointment?.joining_date ? formatDate(response.employee_appointment.joining_date) : 'N/A');
                
                // Format salary
                var salary = response.employee_current_salary?.total_salary;
                $('#empSalary').text(salary ? 'Rs. ' + salary.toLocaleString() : 'N/A');

                // Handle employee photo
                loadEmployeePhoto(response);
                
                // Show modal
                $('#employeeModal').modal('show');
            },
            error: function(xhr) {
                alert('Error loading employee details');
                console.error(xhr);
            }
        });
    }

    function loadEmployeePhoto(employee) {
        var empPhoto = $('#empPhoto');
        var noPhoto = $('#noPhoto');
        console.log('Employee Picture URL:', employee.picture_url);
        // Check if we have a picture URL from the response
        if (employee.picture_url) {
            var imageUrl = "{{ asset('storage') }}/" + employee.picture_url;
            empPhoto.attr('src', imageUrl);
            empPhoto.show();
            noPhoto.hide();
            
            // Handle image loading errors
            empPhoto.on('error', function() {
                empPhoto.hide();
                noPhoto.show();
                empPhoto.attr('src', "{{ asset('Massets/images/default.png') }}");
            });
        } else {
            // If no picture URL, try to construct it or use default
            // You might need to adjust this based on your employeePicture() method
            var defaultImageUrl = "{{ asset('Massets/images/default.png') }}";
            empPhoto.attr('src', defaultImageUrl);
            empPhoto.show();
            noPhoto.hide();
        }
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
});
</script>
@stop