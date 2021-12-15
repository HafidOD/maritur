<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>{{$listName}}</h3>
</div>
<div class="modal-body">
<form action="{{url("admin/tours/update-list/$listName")}}" method="post" class="validate-form ajax-submit" data-reload-tables='true' data-reload-modal-on-success='true'>
	{{ csrf_field() }}
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th>Variante</th>
				<th>Tour</th>
				<th>Agregar</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($tours as $t)
				@foreach ($t->tourPrices as $tp)
					<tr>
						<td>{{$tp->name}}</td>
						<td>{{$t->name}}</td>
						<td><input type="checkbox" name="tourPrices[]" value="{{$tp->id}}" {{$tp->isInList($listName)?'checked':''}}></td>
					</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>
	<div class="text-right">
		<button type="submit" class="btn btn-primary">Actualizar</button>
	</div>
</form>
</div>
