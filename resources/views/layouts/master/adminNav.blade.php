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
                
                 <li class="{{Request::is('dashboard')?'active':''}}"><a id="notInclude" class="waves-effect waves-dark" href="{{url('/dashboard')}}" aria-expanded="false"><i class="fas fa-tachometer-alt"></i><span class="hide-menu">Dashboard </span></a>
                </li>

<!-- HR -->               
                <li class="{{Request::is('hrms/employee*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu">Human Resource</span></a>
                    <ul aria-expanded="false" class="collapse">
                    @can('hr all employees') 
                        <!-- <li ><a class="{{Request::is('hrms/employee/user')?'active':''}}" href="{{url('/hrms/testing')}}">User Detail</a></li>
                      
                        <li><a  class="{{Request::is('hrms/employee/allEmployeeList')?'active':''}}" href="{{route('employee.allEmployeeList')}}">All Employees</a></li> -->
                    @endcan
                   
                    @canany(['hr edit record','hr delete record'])
                        <li><a  class="{{Request::is('hrms/employee/create')?'active':''}}" href="{{route('employee.create')}}">Add Employee</a></li>
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
                @can('hr monthly input')
                <li class="{{Request::is('input/*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-book-open"></i><span class="hide-menu">HR Monthly Report</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('input/inputMonth/create')?'active':''}}" href="{{route('inputMonth.create')}}">Add Month</a></li> 
                    </ul>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('input/inputProject/create')?'active':''}}" href="{{route('inputProject.create')}}">Add Project</a></li> 
                    </ul>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('input/input/create')?'active':''}}" href="{{route('input.create')}}">Add Input</a></li>
                       
                    </ul>
                    
                </li>
                @endcan

<!-- End HR Reports -->

<!-- CV -->               
                @can('cv edit record') 
                <li class="{{Request::is('hrms/cv*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu">CV Records</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/cvData/cv/create')?'active':''}}" href="{{route('cv.create')}}">Add CV</a></li>
                        <li><a class="{{Request::is('hrms/cvData/cv')?'active':''}}" href="{{route('cv.index')}}">List of CVs</a></li>
                         @can('Super Admin')
                         <li><a class="{{Request::is('hrms/cvData/search')?'active':''}}" href="{{route('cv.search')}}">Search</a></li>
                         @endcan
                        
                    </ul>
                </li>
                @endcan

<!-- End CV -->
 
<!-- Project -->
                @canany(['pr edit power','pr edit water'])
               <li class="{{Request::is('hrms/project*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-cubes"></i><span class="hide-menu">Projects</span></a>
                    <ul aria-expanded="false" class="collapse">
                   
                   
                        <li><a  class="{{Request::is('hrms/project/create')?'active':''}}" href="{{route('project.create')}}">Add Project</a></li>
                        <li><a  class="{{Request::is('hrms/project')?'active':''}}" href="{{route('project.index')}}">List of Projects</a></li>

                        <li><a  class="{{Request::is('hrms/projectCode')?'active':''}}" href="{{route('projectCode.create')}}">Project Code Calculator</a></li>
                   
                                               
                    </ul>
                </li>
                @endcanany

<!-- End Project -->


                @can('Super Admin')
<!-- Invoices -->
                <li class="{{Request::is('invoice*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-shopping"></i><span class="hide-menu">Invoices</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('invoice/invoice/create')?'active':''}}" href="{{route('invoice.create')}}">Create Invoice</a></li>
                        <li><a class="{{Request::is('hrms/selfServices/selfContact/create')?'active':''}}" href="" >List of Invoice</a></li>
                        <li><a class="{{Request::is('invoice/invoiceRights/create')?'active':''}}" href="{{route('invoiceRights.create')}}">Invoice Rights</a></li>
                        
                                               
                    </ul>
                </li>
<!-- End Invoices -->
<!-- Self Services -->              
                 <li class="{{Request::is('hrms/charging*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-shopping"></i><span class="hide-menu">Self Services</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/selfServices/selfContact/create')?'active':''}}" href="{{route('selfContact.create')}}">Personal Contact</a></li>
                        
                                               
                    </ul>
                </li>
<!-- Self Services -->

<!-- Submissions -->
                <li class="{{Request::is('hrms/submission*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-book" aria-hidden="true"></i><span class="hide-menu">Submissions</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/submission/create')?'active':''}}" href="{{route('submission.create')}}">Add Submission</a></li>
                        <li><a  class="{{Request::is('hrms/submission')?'active':''}}" href="{{route('submission.index')}}">List of Submissions</a></li>
                        
                                               
                    </ul>
                </li>
               @endcan
<!-- End Submissions -->

<!-- Admin -->
               @can('Super Admin')
                 <li class="{{Request::is('hrms/admin*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fa fa-lock" aria-hidden="true"></i><span class="hide-menu">Admin</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/admin/activeUser')?'active':''}}" href="{{route('activeUser.index')}}">Active User List</a></li>
                        <li><a class="{{Request::is('hrms/admin/lastLogin')?'active':''}}" href="{{route('lastLogin.detail')}}">Last Login Detail</a></li>
                        <li><a class="{{Request::is('hrms/admin/permission')?'active':''}}" href="{{route('permission.index')}}">Permission</a></li>
                                               
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