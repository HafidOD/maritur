<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class='modal-title'>Casa</h3>
</div>
<div class="modal-body">
<form action="{{url('admin/houses/update',['id'=>$model->id])}}" method="post" class="validate-form ajax-submit" data-reload-tables='true'>
	{{ csrf_field() }}
	<div class="text-right">
		<a href='{{ $model->getFullPath() }}' target="_blank" class="btn btn-warning" ><i class="fa fa-eye"></i> Ver</a>
		<a href='{{ url('admin/houses/gallery',['id'=>$model->id]) }}' type="submit" class="btn btn-info" data-toggle='modal-dinamic'><i class="fa fa-file-image-o"></i> Galeria</a>
		<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
	</div>
	<br>
	@include('admin.houses.info');
</form>
</div>
