<div class="card">
    <div class="card-body">
        <h4 class="card-title">Advanced Search</h4>
        <form id="searchForm" method="GET" action="{{ route('hr.reports.employee_list') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_name">Employee Name</label>
                        <input type="text" class="form-control" id="employee_name" name="employee_name"
                            value="{{ request('employee_name') }}" placeholder="Search by name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="designation">Designation</label>
                        <select class="form-control select2" id="designation" name="designation">
                            <option value="">All Designations</option>
                            @foreach($designations as $id => $name)
                            <option value="{{ $id }}" {{ request('designation') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select class="form-control select2" id="department" name="department">
                            <option value="">All Departments</option>
                            @foreach($departments as $id => $name)
                            <option value="{{ $id }}" {{ request('department') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Degree</label>
                        <select class="form-control select2" id="education" name="education">
                            <option value="">All Degrees</option>
                            @foreach($educations as $id => $degree_name)
                            <option value="{{ $id }}" {{ request('education') == $id ? 'selected' : '' }}>{{ $degree_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee_no">Employee No</label>
                        <input type="text" class="form-control" id="employee_no" name="employee_no"
                            value="{{ request('employee_no') }}" placeholder="Search by employee no">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control select2" id="status" name="status">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $id => $name)
                            <option value="{{ $id }}" {{ request('status') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Add more search fields as needed -->
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%', // Make it full width of its container
            dropdownAutoWidth: true, // Auto-adjust dropdown width
        });
    });

    function resetForm() {
        document.getElementById("searchForm").reset();
        window.location = "{{ route('hr.reports.employee_list') }}";
    }
</script>