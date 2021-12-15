<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Edit</h3>
</div>
<div class="modal-body">
<form action="{{url("admin/tour-categories/{$tourCategory->id}")}}" method="post" class="validate-form ajax-submit" data-close-modal-on-success='true' data-reload-tables='true'>
	{{ csrf_field() }}
	{{ method_field('PUT') }}
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="{{$tourCategory->name}}">
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
</form>
</div>
