<style>
.text_requried{
  color: red;
}
</style>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
            <div class="dropdown-menu animated flipInY"> 
           
            </div> 
        <!-- End User profile text-->
       
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                {{--/////Second Start--}}
                
                 <li class="{{Request::is('dashboard')?'active':''}}"><a id="notInclude" class="waves-effect waves-dark navA" href="{{url('/dashboard')}}" aria-expanded="false"><i class="fas fa-tachometer-alt"></i><span class="hide-menu">Dashboard</span></a>
                </li>

<!-- HR -->               
                <li class="{{Request::is('hrms/employee*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu">Human Resource</span></a>
                    <ul aria-expanded="false" class="collapse">
                    @can('hr user data') 
                    @if(Auth::User()->hrEmployee)
                    <li><a  class="{{Request::is('hrms/employee/user/data')?'active':''}}" href="{{route('user.data',Auth::User()->hrEmployee->id??'')}}">User Data</a></li>
                    @endif
                    @endcan
                    @can('hr active employees') 
                        <!-- <li ><a class="{{Request::is('hrms/employee/user')?'active':''}}" href="{{url('/hrms/testing')}}">User Detail</a></li>
                      
                        <li><a  class="{{Request::is('hrms/employee/allEmployeeList')?'active':''}}" href="{{route('employee.allEmployeeList')}}">All Employees</a></li> -->
                        
                       
                        <li><a  class="{{Request::is('hrms/employee/activeEmployeesList')?'active':''}}" href="{{route('employee.activeEmployeesList')}}">Active Employees List</a></li>
                    @endcan
                   

                        @canany(['hr edit record','hr delete record'])
                        <li><a  class="{{Request::is('hrms/employee/create')?'active':''}}" href="{{route('employee.create')}}">Add Employee</a></li>
                        <li><a  class="{{Request::is('hrms/employee/alertList')?'active':''}}" href="{{route('hrAlert.list')}}">Alerts <i class="fas fa-bell" ></i><span class="badge badge-pill badge-danger">{{appointmentExpiryTotal() + cnicExpiryTotal() + drivingLicenceExpiryTotal() + pecCardExpiryTotal()}}</span></a></li>
                        <li><a  class="{{Request::is('hrms/employee/search')?'active':''}}" href="{{route('employee.search')}}">Search</a></li>
                        
                        @endcanany

                        @canany(['hr view record','hr edit record','hr delete record'])
                        <li><a  class="{{Request::is('hrms/employee')?'active':''}}" href="{{route('employee.index')}}">List of Employees</a></li>
                        @endcanany
                      
                    </ul>
                </li>
<!-- End HR -->

<!-- HR Reports -->
                @can('hr reports')
                <li class="{{Request::is('hrms/hrReports*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-open"></i><span class="hide-menu">HR Reports</span></a>

                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/hrReports/list')?'active':''}}" href="{{route('hrReports.list')}}">Reports</a></li>
                       
                    </ul>
                </li>
                @endcan

<!-- End HR Reports -->
<!-- HR Monthly Report -->
             
                <li class="{{Request::is('input/*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-open"></i><span class="hide-menu">Monthly Input</span></a>
                @can('monthly input')
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('input/inputMonth/create')?'active':''}}" href="{{route('inputMonth.create')}}">Add Month</a></li>

                        <li><a class="{{Request::is('input/inputProject/create')?'active':''}}" href="{{route('inputProject.create')}}">Add Project</a></li>

                        <li><a class="{{Request::is('input/input/create')?'active':''}}" href="{{route('input.create')}}">Add Input</a></li>

                        <li><a class="{{Request::is('hrms/input/search')?'active':''}}" href="{{route('input.search')}}">Search</a></li>

                    </ul>
                   
                    

                  @endcan
                </li>
              

<!-- End HR Reports -->


 
<!-- Project -->
               
               <li class="{{Request::is('hrms/project*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-cubes"></i><span class="hide-menu">Projects</span></a>
                    <ul aria-expanded="false" class="collapse">
                   
                        @canany(['pr add water', 'pr add power'])
                        <li><a  class="{{Request::is('hrms/project/create')?'active':''}}" href="{{route('project.create')}}">Add Project</a></li>
                        @endcanany

                        @canany(['pr view water', 'pr view power','pr edit power','pr edit water'])
                        <li><a  class="{{Request::is('hrms/project')?'active':''}}" href="{{route('project.index')}}">List of Projects</a></li>
                        @endcanany


                        @can('pr limited access')
                        <li><a  class="{{Request::is('hrms/project/selectedProjects')?'active':''}}" href="{{route('project.selected')}}">List of Allowed Projects</a></li>
                        @endcan

                        @can('Super Admin')
                        <li><a  class="{{Request::is('hrms/project/progress')?'active':''}}" href="{{route('projectRights.index')}}">Project Rights</a></li> 
                        <li><a  class="{{Request::is('hrms/project/projectRights')?'active':''}}" href="{{route('projectRights.index')}}">Project Rights</a></li> 
                        <li><a  class="{{Request::is('hrms/project/search')?'active':''}}" href="{{route('project.search')}}">Search Documentation</a></li> 
                        @endcan                 
                                               
                    </ul>
                </li>

<!-- End Project -->

<!-- Assets -->               
               
                <li class="{{Request::is('hrms/asset*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-bank"></i><span class="hide-menu">Assets</span></a>
                    <ul aria-expanded="false" class="collapse">  

                        @can('asset edit record') 
                        <li><a class="{{Request::is('hrms/assets/create')?'active':''}}" href="{{route('asset.create')}}">Add Asset</a></li>
                        @endcan

                        @canany(['asset edit record', 'asset view record','asset all record'])
                        <li><a class="{{Request::is('hrms/asset')?'active':''}}" href="{{route('asset.index')}}">List of Assets</a></li> 
                        
                        @endcanany
                         <li><a  class="{{Request::is('hrms/asset/search')?'active':''}}" href="{{route('asset.search')}}">Search</a></li>
                        
                    </ul>
                </li>
                
<!-- End Asset -->

<!-- Leave -->
                <li class="{{Request::is('hrms/*leave*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-calendar-alt" aria-hidden="true"></i><span class="hide-menu">Leave Management</span></a>
                    <ul aria-expanded="false" class="collapse">
                        @can('lev edit record') 
                        <li><a class="{{Request::is('hrms/leave/create')?'active':''}}" href="{{route('leave.create')}}">Apply Leave</a></li>
                        <li><a class="{{Request::is('hrms/leave')?'active':''}}" href="{{route('leave.index')}}">List of Leaves</a></li>
                        <li><a class="{{Request::is('hrms/leave/search')?'active':''}}" href="{{route('leave.search')}}">Search</a></li>
                        @endcan
                        @can('Super Admin')
                        <li><a class="{{Request::is('hrms/leaveBalance')?'active':''}}" href="{{route('leaveBalance.index')}}">Leave Balance</a></li>
                        <li><a class="{{Request::is('hrms/accumulativesLeave')?'active':''}}" href="{{route('accumulativesLeave.index')}}">Accumulatives Leave</a></li>
                        @endcan              
                    </ul>
                </li>
             
<!-- End Leave -->


<!-- CV -->               
               
                <li class="{{Request::is('hrms/cv*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu">CV Records</span></a>
                    <ul aria-expanded="false" class="collapse"> 
                        @can('cv edit record')  
                        <li><a class="{{Request::is('hrms/cvData/cv/create')?'active':''}}" href="{{route('cv.create')}}">Add CV</a></li>
                        <li><a class="{{Request::is('hrms/cvData/cv')?'active':''}}" href="{{route('cv.index')}}">List of CVs</a></li>
                         <li><a class="{{Request::is('hrms/cvData/search')?'active':''}}" href="{{route('cv.search')}}">Search</a></li>
                        @endcan
                        
                    </ul>
                </li>
            

<!-- End CV -->

 @can('self_services edit record')
<!-- Self Services -->              
                 <li class="{{Request::is('hrms/charging*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-shopping"></i><span class="hide-menu">Self Services</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/selfServices/selfContact/create')?'active':''}}" href="{{route('selfContact.create')}}">Personal Contact</a></li>
                        
                                               
                    </ul>
                </li>
<!-- Self Services -->
@endcan


<!-- Admin -->
               @can('Super Admin')
                 <li class="{{Request::is('hrms/admin*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-lock" aria-hidden="true"></i><span class="hide-menu">Admin</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/admin/activeUser')?'active':''}}" href="{{route('activeUser.index')}}">Active User List</a></li>
                        <li><a class="{{Request::is('hrms/admin/lastLogin')?'active':''}}" href="{{route('lastLogin.detail')}}">Last Login Detail</a></li>
                        <li><a class="{{Request::is('hrms/admin/permission/employeePermission')?'active':''}}" href="{{route('permission.search')}}">Emplolyee Permission</a></li>
                        <li><a class="{{Request::is('hrms/admin/permission')?'active':''}}" href="{{route('permission.index')}}">Permissions</a></li>
                        <li><a class="{{Request::is('hrms/admin/audit/search')?'active':''}}" href="{{route('audit.search')}}">Search User Log</a></li>
                                               
                    </ul>
                </li>
               @endcan
<!-- End Admin -->                 
    
               
                {{--///////// Second End--}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
     
</aside>


