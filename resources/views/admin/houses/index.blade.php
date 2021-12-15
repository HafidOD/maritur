@extends('layouts.back')

@section('content')
<a href="{{url('admin/houses/new')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic' data-modal-width='800px'><i class="fa fa-plus"></i> Nueva Casa</a>
<h2>Casas</h2>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/houses/listing'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
	data-search="true"
>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Ciudad</th>
			<th>Imagen</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>
@endsection