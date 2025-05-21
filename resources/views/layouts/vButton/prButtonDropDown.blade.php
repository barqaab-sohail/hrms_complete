<div class="dropdownSubMenue">
    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span id="dropdownSubMenueText" style="font-size:20px"></span>
    </button>

    <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">

        <a type="submit" role="button" id="addProject" href="{{route('project.edit',$data->id)}}" style="color:white" class="dropdown-item btn btn-success " {{Request::is('hrms/project/*/edit')?'style=background-color:#737373,':''}}>Project Detail</a>
        @if(projectProgressRight($data->id))
        <a type="submit" role="button" style="color:white" id="addContractor" href="{{route('projectContractor.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectContractor/index')?'style=background-color:#737373':''}}>Contractor Detail</a>
        <a type="submit" role="button" style="color:white" id="addActualVsSchedule" href="{{route('actualVsScheduledProgress.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/actualVsScheduledProgress/index')?'style=background-color:#737373':''}}>Actual vs Scheduled</a>
        <a type="submit" role="button" style="color:white" id="addDelayReason" href="{{route('delayReason.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/delayReason/index')?'style=background-color:#737373':''}}>Delay Reasons</a>
        <a type="submit" role="button" style="color:white" id="addProjectProgress" href="{{route('projectProgress.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/monthlyProgress/index')?'style=background-color:#737373':''}}>Project Progress</a>
        <a type="submit" role="button" style="color:white" id="addProgressIssue" href="{{route('projectIssues.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectIssues/index')?'style=background-color:#737373':''}}>Critical Issues</a>
        @endif
        @can('pr edit cost')
        <a type="submit" role="button" style="color:white" id="addConsultancyCost" href="{{route('projectConsultancyCost.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectConsultancyCost/index')?'style=background-color:#737373':''}}>Consultancy Cost</a>
        <a type="submit" role="button" style="color:white" id="addPosition" href="{{route('projectPosition.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPosition/index')?'style=background-color:#737373':''}}>Position</a>
        <a type="submit" role="button" style="color:white" id="addDirectCostDetail" href="{{route('directCostDetail.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/directCostDetail/index')?'style=background-color:#737373':''}}>Direct Cost Detail</a>
        @if($data->sub_projects===1)
        <a type="submit" role="button" style="color:white" id="addSubProject" href="{{route('subProject.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectIssues/index')?'style=background-color:#737373':''}}>Sub Projects</a>
        @endif
        @if($data->pr_role_id!=1)
        <a type="submit" role="button" style="color:white" id="addPartner" href="{{route('projectPartner.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPartner/index')?'style=background-color:#737373':''}}>Project Partners</a>
        @endif
        <a type="submit" role="button" style="color:white" id="addStaff" href="{{route('projectStaff.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectStaff/index')?'style=background-color:#737373':''}}>Project Staff</a>
        @endcan
        @if(isViewInvoice($data->id) || isEditInvoice($data->id) || isDeleteInvoice($data->id) || auth()->user()->can('pr invoice'))
        <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectInvoice/index')?'style=background-color:#737373':''}}>Invoices</a>
        @if($data->contract_type_id ==2)
        <a type="submit" role="button" style="color:white" id="addMmUtilization" href="{{route('mmUtilization.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/mmUtilization/index')?'style=background-color:#737373':''}}>Man Month Utilization</a>
        <a type="submit" role="button" style="color:white" id="addDirectCostUtilization" href="{{route('prDirectCostUtilization.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/prDirectCostUtilization/index')?'style=background-color:#737373':''}}>Direct Cost Utilization</a>
        @endif
        <a type="submit" role="button" style="color:white" id="addExpense" href="{{route('projectMonthlyExpense.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectMonthlyExpense/index')?'style=background-color:#737373':''}}>Monthly Expenses</a>
        @endif
        @can('pr ledger activity')
        <a type="submit" role="button" style="color:white" id="addProgressActivities" href="{{route('projectLedgerActivity.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectLedgerActivity/index')?'style=background-color:#737373':''}}>Ledger Activity</a>
        @endcan
        @if(projectPaymentRight($data->id))
        <a type="submit" role="button" style="color:white" id="addPayment" href="{{route('projectPayment.show', $data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPayment/index')?'style=background-color:#737373':''}}>Payments</a>
        @endif
        @can('pr view progress')
        <a type="submit" role="button" id="addProgress" style="color:white" href="{{route('projectProgress.chart')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectProgress/chart')?'style=background-color:#737373':''}}>Progress Status</a>
        @endcan

        @can('pr view invoice')
        <!-- <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.chart')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectInvoice/chart')?'style=background-color:#737373':''}}>Invoice Status</a> -->
        @endcan

        @can('pr view documentation')
        <a type="submit" role="button" style="color:white" id="addDocument" href="{{route('projectDocument.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectDocument/show')?'style=background-color:#737373':''}}>Documentation</a>
        @endcan



        @can('Super Admin')
        <a type="submit" role="button" style="color:white" id="addProgressActivities" href="{{route('projectProgressActivities.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectProgressActivities/index')?'style=background-color:#737373':''}}>Progress Activities</a>
        <a type="submit" role="button" style="color:white" id="addMonthlyProgress" href="{{route('monthlyProgress.show',$data->id)}}" class="dropdown-item btn btn-success " {Request::is('hrms/monthlyProgress/index')?'style=background-color:#737373':''}}>Monthly Progress</a>
        @endcan





    </div>
</div>