@extends('layouts.front')

@section('content')
<section class="main">
<div class="container">
	<!-- iFrame de Interación de Europamundo Vacaciones -->

	<iframe id='frame_cmp' src='https://www.europamundo.com/embed_v2/multibuscador.aspx?opeIP=45&ageKEY=22900' frameborder='0'
	scrolling='auto' style='width:100%; height:2850px; overflow-x: hidden;'></iframe>

	<!-- fin iframe -->
</div>	
</section>
@endsection