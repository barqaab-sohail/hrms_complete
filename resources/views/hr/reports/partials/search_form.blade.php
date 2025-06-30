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
                        <select class="form-control select2" id="designation" name="designation[]" multiple="multiple">
                            @foreach($designations as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, (array)request('designation')) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select class="form-control select2" id="department" name="department[]" multiple="multiple">
                            @foreach($departments as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, (array)request('department')) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="department">Degree</label>
                        <select class="form-control select2" id="education" name="education[]" multiple="multiple">
                            @foreach($educations as $id => $degree_name)
                            <option value="{{ $id }}" {{ in_array($id, (array)request('education')) ? 'selected' : '' }}>{{ $degree_name }}</option>
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
            width: '100%',
            dropdownAutoWidth: true,
            placeholder: "Select options",
            allowClear: true
        });
        
        // Handle form submission via AJAX
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $('#employees-table').DataTable().ajax.reload();
        });
    });

    function resetForm() {
        $('#searchForm')[0].reset();
        $('.select2').val(null).trigger('change');
        $('#employees-table').DataTable().ajax.reload();
    }
</script>