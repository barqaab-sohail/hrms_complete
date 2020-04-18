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
               
                <li class="{{Request::is('hrms*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-user"></i><span class="hide-menu">Human Resource</span></a>
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
                @can('Super Admin') 
                <li class="{{Request::is('hrms/cv*')?'active':''}}"> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="mdi mdi-database"></i><span class="hide-menu">CV Records</span></a>
                    <ul aria-expanded="false" class="collapse">  
                        <li><a class="{{Request::is('hrms/cvData/cv/create')?'active':''}}" href="{{route('cv.create')}}">Add CV</a></li>
                        <li><a class="{{Request::is('hrms/cvData/cv')?'active':''}}" href="{{route('cv.index')}}">List of CVs</a></li> 
                        <li><a href="">Services</a></li>  
                       
                    </ul>
                </li>
               @endcan
                 
    
               
                {{--///////// Second End--}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
     
</aside>