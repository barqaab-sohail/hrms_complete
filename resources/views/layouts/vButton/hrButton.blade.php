<div class="btn-group-vertical" role="group" aria-label="vertical button group">

         
          <br>
          

            <a type="submit" role="button" id="addEmployee" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/*/edit')?'style=background-color:#737373':''}}>Employee Information</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Appointment Detail</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Contact Detail</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Emergency Contact</a>
            <a type="submit" id="addEducation" role="button" href="{{route('education.create')}}" class="btn btn-info" {{Request::is('hrms/education/create')?'style=background-color:#737373':''}}>Education</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Promotion</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Transfer/Posting</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Employee Documentation</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>EOBI</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Membership</a>     
             
</div>