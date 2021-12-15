@extends('layouts.back')

@section('content')
<h2>Reservaciones</h2>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/reservations/listing'
	data-height='500'
	data-side-pagination='server'
	data-page-size='10'
	data-pagination='true'
	{{-- data-search="true" --}}
>
	<thead>
		<tr>
			<th data-sortable='true'>ID</th>
			<th>Cliente</th>
			<th>Hotel</th>
			<th>Habitaciones</th>
			<th>Tours</th>
			<th>Transportacion</th>
			<th>Total</th>
			<th>Fecha</th>
			<th>Afiliado</th>
			<th>Estado</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>
@endsection