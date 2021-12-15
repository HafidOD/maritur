<a 
	href="{{url('admin/item-relations/new',[$itemList->id])}}" 
	class='btn btn-primary pull-right' 
	data-toggle='modal-dinamic'>
	<i class="fa fa-plus"></i> Agregar elemento</a>
<h3>Elementos</h3>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/item-relations/listing?item-list={{$itemList->id}}'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
	{{-- data-search="true" --}}
>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Precio</th>
			<th>Moneda</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>