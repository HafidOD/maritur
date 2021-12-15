@extends('layouts.back')

@section('content')
	<a href="{{url('admin/tours/new')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic' data-modal-width='800px'><i class="fa fa-plus"></i> Nuevo Tour</a>
<h2>Tours</h2>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/tours/listing'
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
			<th>Variantes</th>
			<th>Afiliado</th>
			<th>Estado</th>
			<th>Imagen</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>
@if (Gate::allows('users'))
	<div class="row">
		<div class="col-md-6">
			<a href="{{url('admin/tour-providers/create')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic' data-modal-width='800px'><i class="fa fa-plus"></i> Nuevo Proveedor</a>
			<h2>Proveedores</h2>
			<hr>
			<table
				class="table table-condensed table-hover table-bordered"
				data-toggle='table'
				data-url='/admin/tour-providers/listing'
				data-height='350'
				data-side-pagination='server'
				data-page-size='50'
				data-pagination='true'
				{{-- data-search="true" --}}
			>
				<thead>
					<tr>
						<th>Name</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
			</table>		
		</div>
		<div class="col-md-6">
			<a href="{{url('admin/tour-categories/create')}}" class='btn btn-primary pull-right' data-toggle='modal-dinamic' data-modal-width='800px'><i class="fa fa-plus"></i> Nueva categoria</a>
			<h2>Categorias</h2>
			<hr>
			<table
				class="table table-condensed table-hover table-bordered"
				data-toggle='table'
				data-url='/admin/tour-categories/listing'
				data-height='350'
				data-side-pagination='server'
				data-page-size='50'
				data-pagination='true'
				{{-- data-search="true" --}}
			>
				<thead>
					<tr>
						<th>Name</th>
						<th>Icono</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
			</table>		
		</div>
	</div>
@endif
@endsection