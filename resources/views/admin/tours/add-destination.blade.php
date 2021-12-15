<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Destino cercano a <?= $tour->name ?></h3>
</div>
<div class="modal-body">
	<form action="{{url('admin/tours/add-destination',[$tour->id])}}" method="post" class="validate-form ajax-submit" close-modal-on-success='true' data-reload-tables='true'>
		{{ csrf_field() }}
		<div class="form-group">
			<label>Destino</label>
			<input type="text" class="form-control autocomplete-ajax" data-ajax="{{url('admin/destinations/search')}}">
			<input type="hidden" name="destinationId" >
		</div>
		<button type="submit" class="btn btn-primary">Agregar</button>
	</form>
</div>
