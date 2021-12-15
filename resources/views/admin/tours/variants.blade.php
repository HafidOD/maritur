<h3>
	Variantes
	@if ($edit)
		<a href='{{ url('admin/tour-prices/new',['id'=>$tour->id]) }}' class="btn btn-info" data-toggle='modal-dinamic'><i class="fa fa-plus"></i> Nuevo</a>
	@endif
</h3>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/tour-prices/listing/{{$tour->id}}'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
>
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Precio Adulto</th>
			<th>Precio Niño</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>