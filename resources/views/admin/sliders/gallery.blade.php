<div class="modal-header">
	<h3 class='modal-title'>Galería de {{ $sectionName }}</h3>
</div>
<div class="modal-body">
	@if (count($images)>0)
	<div 
		id='testsort' 
		class="sortable-images row" 
		data-update-positions-url='{{ url('admin/uploads/update-positions') }}'>
		@foreach ($images as $img)
			<div class="col-xs-3" data-img='{{ $img->id }}'>
			<a href="{{ $img->getFileUrl() }}" target="_blank">
				<img src="{{ $img->getFileUrl() }}">
			</a>
				<br>
				<br>
				<div class="text-center">
					<a href='{{ url('admin/uploads/delete',['id'=>$img->id]) }}' class='ajax-link show-warning btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>
					<button type='button' class='btn btn-info btn-xs btn-move'><i class='fa fa-arrows'></i></button>
				</div>
			</div>
		@endforeach
	</div>
	@else
		<h3 class="text-center">No se han cargado imágenes</h3>
	@endif
	<hr>
	<div class="row">
		<div class="col-xs-4">
			<form 
				data-reload-modal-on-success='true' 
				class='ajax-submit validate-form' 
				method='post' 
				action='{{ url('admin/sliders/uploads/add') }}'>
			{{ csrf_field() }}
			<input type="hidden" name="referenceId" value="{{$section}}">
			<input type="hidden" name="folder" value="sliders">
			<div class="form-group">
		        <label>Agregar imágenes</label>
		        <input data-container='#galeryPreview' class='imgField' type="file" name="images[]" accept=".jpg,.png,.jpeg" multiple required>			
			</div>
			<button type='submit' class='btn btn-primary'>Subir</button>
			</form>
		</div>
		<div class="col-xs-8 images-previews images-3" id='galeryPreview'></div>
	</div>
</div>
<div class="modal-footer text-left">
	<div class="text-left">
		<button type='button' data-dismiss='modal' class='btn btn-default'>Cerrar</button>
	</div>
</div>
<script type="text/javascript">
	 $("#testsort").sortable({
 	cancel:"a",
 	update:function(e,ui){
 		var positions = {};
 		$(this).children().each(function(index,value){
 			positions[$(value).data('img')]=index;
 		});
 		$.post($(this).data('update-positions-url'),{positions:positions,csrf_token:'{{csrf_token()}}'},function(r){
 			showAlert('Orden actualizado','success');
 		});
 	}
 });
</script>