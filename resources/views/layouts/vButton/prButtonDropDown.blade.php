<div class="dropdownSubMenue">
    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span id="dropdownSubMenueText" style="font-size:20px"></span>
    </button>

    <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">

        <a type="submit" role="button" id="addProject" href="{{route('project.edit',session('pr_detail_id'))}}" style="color:white" class="dropdown-item btn btn-success " {{Request::is('hrms/project/*/edit')?'style=background-color:#737373,':''}}>Project Detail</a>
        @can('pr edit cost')
        <a type="submit" role="button" style="color:white" id="addConsultancyCost" href="{{route('projectConsultancyCost.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectConsultancyCost/index')?'style=background-color:#737373':''}}>Consultancy Cost</a>
        <a type="submit" role="button" style="color:white" id="addPosition" href="{{route('projectPosition.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPosition/index')?'style=background-color:#737373':''}}>Position</a>
        @if($data->sub_projects===1)
        <a type="submit" role="button" style="color:white" id="addSubProject" href="{{route('subProject.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectIssues/index')?'style=background-color:#737373':''}}>Sub Projects</a>
        @endif
        @if($data->pr_role_id!=1)
        <a type="submit" role="button" style="color:white" id="addPartner" href="{{route('projectPartner.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPartner/index')?'style=background-color:#737373':''}}>Project Partners</a>
        @endif
        <a type="submit" role="button" style="color:white" id="addStaff" href="{{route('projectStaff.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectStaff/index')?'style=background-color:#737373':''}}>Project Staff</a>
        @endcan
        @if(isViewInvoice(session('pr_detail_id')) || isEditInvoice(session('pr_detail_id')) || isDeleteInvoice(session('pr_detail_id')))
        <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectInvoice/index')?'style=background-color:#737373':''}}>Invoices</a>
        <a type="submit" role="button" style="color:white" id="addExpense" href="{{route('projectMonthlyExpense.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectMonthlyExpense/index')?'style=background-color:#737373':''}}>Monthly Expenses</a>
        @endif

        @if(projectPaymentRight(session('pr_detail_id')))
        <a type="submit" role="button" style="color:white" id="addPayment" href="{{route('projectPayment.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPayment/index')?'style=background-color:#737373':''}}>Payments</a>
        @endif
        @can('pr view progress')
        <a type="submit" role="button" id="addProgress" style="color:white" href="{{route('projectProgress.chart')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectProgress/chart')?'style=background-color:#737373':''}}>Progress Status</a>
        @endcan

        @can('pr view invoice')
        <!-- <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.chart')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectInvoice/chart')?'style=background-color:#737373':''}}>Invoice Status</a> -->
        @endcan

        @can('pr view documentation')
        <a type="submit" role="button" style="color:white" id="addDocument" href="{{route('projectDocument.create')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectDocument/create')?'style=background-color:#737373':''}}>Documentation</a>
        @endcan



        @if(projectProgressRight(session('pr_detail_id')))
        <a type="submit" role="button" style="color:white" id="addContractor" href="{{route('projectContractor.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectContractor/index')?'style=background-color:#737373':''}}>Contractor Detail</a>
        <a type="submit" role="button" style="color:white" id="addProjectProgress" href="{{route('projectProgress.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/monthlyProgress/index')?'style=background-color:#737373':''}}>Project Progress</a>
        <a type="submit" role="button" style="color:white" id="addProgressIssue" href="{{route('projectIssues.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectIssues/index')?'style=background-color:#737373':''}}>Critical Issues</a>
        @endif

        @can('Super Admin')
        <a type="submit" role="button" style="color:white" id="addProgressActivities" href="{{route('projectProgressActivities.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectProgressActivities/index')?'style=background-color:#737373':''}}>Progress Activities</a>
        <a type="submit" role="button" style="color:white" id="addMonthlyProgress" href="{{route('monthlyProgress.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/monthlyProgress/index')?'style=background-color:#737373':''}}>Monthly Progress</a>
        @endcan





    </div>
</div>