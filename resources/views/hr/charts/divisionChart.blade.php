@section('content')
	<div class="card">
		<div class="card-body">
				
		<h2 class="box-title">Departmentwise Charts</h2>
			<div id="app">
            {!! $chart->container() !!}
        </div>

        <script src="https://unpkg.com/vue"></script>

        <script>
            var app = new Vue({ 
                el: '#app',
            });     
        </script>
        
         {!! $chart->script() !!}
			
		</div>
	</div>

@stop