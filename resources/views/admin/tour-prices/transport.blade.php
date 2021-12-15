<h3>
	Costos de transportación
	<a href='{{ url('admin/tour-price-transportations/new',['id'=>$tourPrice->id]) }}' class="btn btn-info" data-toggle='modal-dinamic'><i class="fa fa-plus"></i> Nuevo</a>
</h3>
<hr>
<table
	class="table table-condensed table-hover table-bordered"
	data-toggle='table'
	data-url='/admin/tour-price-transportations/listing/{{$tourPrice->id}}'
	data-height='350'
	data-side-pagination='server'
	data-page-size='50'
	data-pagination='true'
>
	<thead>
		<tr>
			<th>Ciudad Origen</th>
			<th>Precio Adulto</th>
			<th>Precio Niño</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
</table>