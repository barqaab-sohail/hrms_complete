<div class="dropdown1">
    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span id="show" style="font-size:20px"></span>
  </button>

    <div class="dropdown-menu" role="group" aria-labelledby="dropdownMenuButton" style="width: 100%;">
             
              <br>
 
                <a type="submit" role="button" id="addProject" href="{{route('project.edit',session('pr_detail_id'))}}" style="color:white" class="dropdown-item btn btn-success projetButton" {{Request::is('hrms/project/*/edit')?'style=background-color:#737373,':''}}>Project Detail</a>
                
                @can('pr view progress') 
                <a type="submit" role="button" id="addProgress" style="color:white" href="{{route('projectProgress.chart')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectProgress/chart')?'style=background-color:#737373':''}}>Progress Status</a>
                @endcan

                @can('pr view invoice') 
                <!-- <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.chart')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectInvoice/chart')?'style=background-color:#737373':''}}>Invoice Status</a> -->
                @endcan

                @can('pr view documentation') 
                <a type="submit" role="button" style="color:white" id="addDocument" href="{{route('projectDocument.create')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectDocument/create')?'style=background-color:#737373':''}}>Documentation</a>
                @endcan
                
                
                @can('Super Admin')
                @if($data->pr_role_id!=1)
                <a type="submit" role="button" style="color:white" id="addPosition" href="{{route('projectPartner.create')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectPartner/create')?'style=background-color:#737373':''}}>Partner Detail</a>
                @endif
                <a type="submit" role="button" style="color:white" id="addConsultancyCost" href="{{route('projectConsultancyCost.index')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectConsultancyCost/index')?'style=background-color:#737373':''}}>Consultancy Cost</a>

                <a type="submit" role="button" style="color:white" id="addInvoice" href="{{route('projectInvoice.index')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectInvoice/index')?'style=background-color:#737373':''}}>Invoices</a>

                <a type="submit" role="button" style="color:white" id="addPayment" href="{{route('projectPayment.index')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectPayment/index')?'style=background-color:#737373':''}}>Payments</a>

                <a type="submit" role="button" style="color:white" id="addPosition" href="{{route('projectPosition.create')}}" class="dropdown-item btn btn-success projetButton" {Request::is('hrms/projectPosition/create')?'style=background-color:#737373':''}}>Position</a>
                @endcan
            
                <br>
                
               
             <style>
                
            .btn-success:active { background-color: red; }
                
            </style>

              <br>



                 
    </div>
</div>
<style>
    .dropdown:hover .dropdown-menu{
        display: block;
    }
    .dropdown-menu{
        margin-top: 0;
    }
</style>

<script type="text/javascript">

$('.projetButton').on('click', function(){
    $('#show').text($(this).text());
   
});

$(document).ready(function() {

  $('#show').text($("a[id='addProject']").text());


   $(".dropdown1").hover(function(){
        $(this).addClass('dropdown');
        var dropdownMenu = $(this).children(".dropdown-menu");
        if(dropdownMenu.is(":visible")){
            dropdownMenu.parent().toggleClass("open");

            $('.dropdown-item').click(function(){
                $(".dropdown").removeClass("dropdown");
            });
        }   
    });


});



</script>