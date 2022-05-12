<div class="dropdownSubMenue">
    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span id="dropdownSubMenueText" style="font-size:20px"></span>
    </button>

    <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">   
             
                <a type="submit" role="button" id="addProject" href="{{route('project.edit',session('pr_detail_id'))}}" style="color:white" class="dropdown-item btn btn-success " {{Request::is('hrms/project/*/edit')?'style=background-color:#737373,':''}}>Project Detail</a>
                
                @can('pr view progress') 
                <a type="submit" role="button" id="addProgress" style="color:white" href="{{route('projectProgress.chart')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectProgress/chart')?'style=background-color:#737373':''}}>Progress Status</a>
                @endcan

                @can('pr view invoice') 
                <!-- <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.chart')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectInvoice/chart')?'style=background-color:#737373':''}}>Invoice Status</a> -->
                @endcan

                @can('pr view documentation') 
                <a type="submit" role="button" style="color:white" id="addDocument" href="{{route('projectDocument.create')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectDocument/create')?'style=background-color:#737373':''}}>Documentation</a>
                @endcan

                @if(isViewInvoice(session('pr_detail_id')) || isEditInvoice(session('pr_detail_id')) || isDeleteInvoice(session('pr_detail_id')))
                <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectInvoice/index')?'style=background-color:#737373':''}}>Invoices</a>
                @endif

                @if(projectPaymentRight(session('pr_detail_id')))
                <a type="submit" role="button" style="color:white" id="addPayment" href="{{route('projectPayment.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPayment/index')?'style=background-color:#737373':''}}>Payments</a>
                @endif

                @if(projectProgressRight(session('pr_detail_id')))
                 <a type="submit" role="button" style="color:white" id="addProgressActivities" href="{{route('projectProgressActivities.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectProgressActivities/index')?'style=background-color:#737373':''}}>Progress Activities</a>
                 <a type="submit" role="button" style="color:white" id="addProjectProgress" href="{{route('projectProgress.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/monthlyProgress/index')?'style=background-color:#737373':''}}>Project Progress</a>
                 <a type="submit" role="button" style="color:white" id="addMonthlyProgress" href="{{route('monthlyProgress.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/monthlyProgress/index')?'style=background-color:#737373':''}}>Monthly Progress</a>
                @endif
                
                
                @can('Super Admin')
                @if($data->pr_role_id!=1)
                <a type="submit" role="button" style="color:white" id="addPartner" href="{{route('projectPartner.create')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPartner/create')?'style=background-color:#737373':''}}>Partner Detail</a>
                @endif
                <a type="submit" role="button" style="color:white" id="addConsultancyCost" href="{{route('projectConsultancyCost.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectConsultancyCost/index')?'style=background-color:#737373':''}}>Consultancy Cost</a>

                <a type="submit" role="button" style="color:white" id="addPosition" href="{{route('projectPosition.index')}}" class="dropdown-item btn btn-success " {Request::is('hrms/projectPosition/index')?'style=background-color:#737373':''}}>Position</a>

        


                @endcan



                 
    </div>
</div>
