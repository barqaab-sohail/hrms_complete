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
               
                <li class="{{Request::is('hrms/employee*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu">Human Resource</span></a>
                    <ul aria-expanded="false" class="collapse">
                    @can('Super Admin') 
                        <li ><a class="{{Request::is('hrms/employee/user')?'active':''}}" href="{{url('/hrms/testing')}}">User Detail</a></li>
                    @endcan
                    @canany(['hr edit record','hr delete record'])
                        <li><a  class="{{Request::is('hrms/employee/create')?'active':''}}" href="{{route('employee.create')}}">Add Employee</a></li>
                        <li><a  class="{{Request::is('hrms/employee')?'active':''}}" href="{{route('employee.index')}}">List of Employees</a></li>
                    @endcanany
                      
                    </ul>
                </li>
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

               <li class="{{Request::is('hrms/project*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-cubes"></i><span class="hide-menu">Projects</span></a>
                    <ul aria-expanded="false" class="collapse">
                   
                    @canany(['project edit record','project delete record'])
                        <li><a  class="{{Request::is('hrms/project/create')?'active':''}}" href="{{route('project.create')}}">Add Project</a></li>
                        <li><a  class="{{Request::is('hrms/project')?'active':''}}" href="{{route('project.index')}}">List of Projects</a></li>
                    @endcanany
                      
                    </ul>
                </li>

               @can('Super Admin')
                 <li class="{{Request::is('hrms/admin*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu">Admin</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/admin/activeUser')?'active':''}}" href="{{route('activeUser.index')}}">Active User List</a></li>
                        <li><a class="{{Request::is('hrms/admin/permission')?'active':''}}" href="{{route('permission.index')}}">Permission</a></li>
                                               
                    </ul>
                </li>
               @endcan
                 
    
               
                {{--///////// Second End--}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
     
</aside>