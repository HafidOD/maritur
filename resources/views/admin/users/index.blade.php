@extends('layouts.back')

@section('content')
<a href="{{url('admin/users/create')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic'><i class="fa fa-plus"></i> Nuevo usuario</a>
<h2>Usuarios</h2>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/users/listing'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
	data-search="true"
>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Email</th>
			<th>Cuenta afiliado</th>
			<th>Rol</th>
			<th>Estado</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>
@endsection