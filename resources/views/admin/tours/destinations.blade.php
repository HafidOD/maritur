<h2>
	Destinos cercanos
	<a href='{{ url('admin/tours/add-destination',[$tour->id]) }}' class="btn btn-info" data-toggle='modal-dinamic'><i class="fa fa-plus"></i> Nuevo</a>
</h2>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='{{url('admin/tours/destinations-listing',[$tour->id])}}'
	data-height='350'
	data-side-pagination='server'
	data-page-size='10'
	data-pagination='true'
	{{-- data-search="true" --}}
>
	<thead>
		<tr>
			<th data-sortable='true'>ID</th>
			<th class="text-center">Eliminar</th>
		</tr>
	</thead>
</table>