<div class="btn-group-vertical" role="group" aria-label="vertical button group" style="width: 100%;">
            <a type="submit" role="button" id="addProject" href="{{route('project.edit',session('pr_detail_id'))}}" class="btn btn-success" {{Request::is('hrms/project/*/edit')?'style=background-color:#737373':''}}>Project Detail</a>
            
            @can('pr view progress') 
            <a type="submit" role="button" id="addProgress" href="{{route('projectProgress.chart')}}" class="btn btn-success" {Request::is('hrms/projectProgress/chart')?'style=background-color:#737373':''}}>Progress Status</a>
            @endcan

            @can('pr view invoice') 
            <a type="submit" role="button" id="addInvoice" href="{{route('projectInvoice.chart')}}" class="btn btn-success" {Request::is('hrms/projectInvoice/chart')?'style=background-color:#737373':''}}>Invoice Status</a>
            @endcan

            @can('pr view documentation') 
            <a type="submit" role="button" id="addDocument" href="{{route('projectDocument.create')}}" class="btn btn-success" {Request::is('hrms/projectDocument/create')?'style=background-color:#737373':''}}>Documentation</a>
            @endcan
            
            
            @can('Super Admin')
            @if($data->pr_role_id!=1)
            <a type="submit" role="button" id="addPosition" href="{{route('projectPartner.create')}}" class="btn btn-success" {Request::is('hrms/projectPartner/create')?'style=background-color:#737373':''}}>Partner Detail</a>
            @endif
            <a type="submit" role="button" id="addConsultancyCost" href="{{route('projectConsultancyCost.index')}}" class="btn btn-success" {Request::is('hrms/projectConsultancyCost/index')?'style=background-color:#737373':''}}>Consultancy Cost</a>
            <a type="submit" role="button" id="addInvoice" href="{{route('projectInvoice.index')}}" class="btn btn-success" {Request::is('hrms/projectInvoice/index')?'style=background-color:#737373':''}}>Invoices</a>
            <a type="submit" role="button" id="addPosition" href="{{route('projectPosition.create')}}" class="btn btn-success" {Request::is('hrms/projectPosition/create')?'style=background-color:#737373':''}}>Position</a>
            

            @endcan
             
</div>