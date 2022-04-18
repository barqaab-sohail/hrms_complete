<div class="dropdownSubMenue">
    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span id="dropdownSubMenueText" style="font-size:20px"></span>
  </button>

      <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">
      
            <a type="submit" role="button" style="color:white" id="addEmployee" href="{{route('employee.edit',session('hr_employee_id'))}}" class="dropdown-item btn btn-success " {{Request::is('hrms/employee/*/edit')?'style=background-color:#737373':''}}>Employee Information</a>

            @canany(['hr edit appointment','hr view appointment'])
            <a type="submit" id="addAppointment" style="color:white" role="button" href="{{route('appointment.edit',session('hr_employee_id'))}}" class="dropdown-item btn btn-success " {{Request::is('hrms/appointment/')?'style=background-color:#737373':''}}>Appointment Detail</a>
            @endcan
            
            @canany(['hr edit contact','hr view contact'])
            <a type="submit" id="addContact" style="color:white" role="button" href="{{route('contact.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/contact/create')?'style=background-color:#737373':''}}>Contact Detail</a>

            <a type="submit" id="addEmergency" style="color:white" role="button" href="{{route('emergency.edit',session('hr_employee_id'))}}" class="dropdown-item btn btn-success " {{Request::is('hrms/emergency/')?'style=background-color:#737373':''}}>Emergency Contact</a>

            <a type="submit" id="addNextToKin" style="color:white" role="button" href="{{route('nextToKin.edit',session('hr_employee_id'))}}" class="dropdown-item btn btn-success " {{Request::is('hrms/nextToKin/')?'style=background-color:#737373':''}}>Next to Kin</a>
            <a type="submit" id="addAdditionalInformation" style="color:white" role="button" href="{{route('additionalInformation.edit',session('hr_employee_id'))}}" class="dropdown-item btn btn-success " {{Request::is('hrms/additionalInformation/')?'style=background-color:#737373':''}}>Additional Information</a>
            @endcan
           
            @canany(['hr edit education','hr view education'])
            <a type="submit" id="addEducation" style="color:white" role="button" href="{{route('education.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/education/create')?'style=background-color:#737373':''}}>Education</a>
            @endcan

            @canany(['hr edit experience','hr view experience'])
            <a type="submit" id="addExperience" style="color:white" role="button" href="{{route('experience.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/experience/create')?'style=background-color:#737373':''}}>Experience</a>
            @endcan

            @canany(['hr edit promotion','hr view promotion'])
            <a type="submit" role="button" style="color:white" id="addPromotion"  href="{{route('promotion.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/promotion/create')?'style=background-color:#737373':''}}>Promotion</a>
            @endcan

            @canany(['hr edit posting','hr view posting'])
             <a type="submit" role="button" style="color:white" id="addPosting" href="{{route('posting.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/posting/create')?'style=background-color:#737373':''}}>Transfer/Posting</a>
            @endcan

            @canany(['hr edit documentation','hr view documentation'])
            <a type="submit" role="button" style="color:white" id="addDocumentation"  href="{{route('documentation.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/documentation/create')?'style=background-color:#737373':''}}>Employee Documentation</a> 
            @endcan 


            @can('hr edit exit')
            <a type="submit" id="addExit"  style="color:white" role="button" href="{{route('exit.create')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/exit/create/')?'style=background-color:#737373':''}}>Exit</a>
            <a type="submit" role="button" style="color:white" id="addManager" href="{{route('manager.index')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/manager/index')?'style=background-color:#737373':''}}>HOD</a>
            @endcan

            @can('hr edit salary')
            <a type="submit" role="button" style="color:white" id="addSalary" href="{{route('employeeSalary.index')}}" class="dropdown-item btn btn-success " {{Request::is('hrms/employeeSalary/index')?'style=background-color:#737373':''}}>Salary</a>
            @endcan

            @can('Super Admin')
            <a type="submit" id="addUserLogin" style="color:white" role="button" href="{{route('userLogin.edit',session('hr_employee_id'))}}" class="dropdown-item btn btn-success " {{Request::is('hrms/userLogin/')?'style=background-color:#737373':''}}>User Rights Detail</a> 
            @endcan
            
      </div>
             
</div>

