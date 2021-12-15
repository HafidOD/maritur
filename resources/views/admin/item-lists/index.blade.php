@extends('layouts.back')

@section('content')
<a href="{{url('admin/item-lists/create')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic'><i class="fa fa-plus"></i> Nueva Lista</a>
<h2>Listas de ofertas</h2>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/item-lists/listing?type=1'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
	{{-- data-search="true" --}}
>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Posición</th>
			<th>Tipo</th>
			<th>Sección</th>
			<th>Elementos</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>
@endsection