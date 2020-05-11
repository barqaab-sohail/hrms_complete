<div class="btn-group-vertical" role="group" aria-label="vertical button group">

         
          <br>
          

            <a type="submit" role="button" id="addEmployee" href="{{route('employee.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/*/edit')?'style=background-color:#737373':''}}>Employee Information</a>
            
            @can('hr edit contact')
            <a type="submit" id="addContact" role="button" href="{{route('contact.create')}}" class="btn btn-info" {{Request::is('hrms/contact/create')?'style=background-color:#737373':''}}>Contact Detail</a>

            <a type="submit" id="addEmergency" role="button" href="{{route('emergency.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/emergency/')?'style=background-color:#737373':''}}>Emergency Contact</a>

            <a type="submit" id="addNextToKin" role="button" href="{{route('nextToKin.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/nextToKin/')?'style=background-color:#737373':''}}>Next to Kin</a>
            @endcan
            @can('hr edit appointment')
            <a type="submit" id="addAppointment" role="button" href="{{route('appointment.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/appointment/')?'style=background-color:#737373':''}}>Appointment Detail</a>
            <a type="submit" id="addEducation" role="button" href="{{route('education.create')}}" class="btn btn-info" {{Request::is('hrms/education/create')?'style=background-color:#737373':''}}>Education</a>
            <a type="submit" id="addExperience" role="button" href="{{route('experience.create')}}" class="btn btn-info" {{Request::is('hrms/experience/create')?'style=background-color:#737373':''}}>Experience</a>
            
            @endcan
            @can('Super Admin')
            <a type="submit" id="addUserLogin" role="button" href="{{route('userLogin.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/userLogin/')?'style=background-color:#737373':''}}>User Login Detail</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Emergency Contact</a>
            
            



            <a type="submit" role="button" href="{{route('employee.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Promotion</a>
            <a type="submit" role="button" href="{{route('employee.edit',session('hr_employee_id'))}}" class="btn btn-info" {{Request::is('hrms/employee/')?'style=background-color:#737373':''}}>Transfer/Posting</a>
            @endcan
            <a type="submit" role="button" id="addDocumentation"  href="{{route('documentation.create')}}" class="btn btn-info" {{Request::is('hrms/documentation/create')?'style=background-color:#737373':''}}>Employee Documentation</a>   
             
</div>