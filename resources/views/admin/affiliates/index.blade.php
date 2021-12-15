@extends('layouts.back')

@section('content')
<a href="{{url('admin/affiliates/create')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic'><i class="fa fa-plus"></i> Nuevo afiliado</a>
<h2>Afiliados</h2>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/affiliates/listing'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
	data-search="true"
>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Dominio</th>
			<th>Estado</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>
@endsection