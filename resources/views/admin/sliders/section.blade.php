<div class="modal-header">
	<h3 class='modal-title'>Imágenes de {{ $sectionName }}</h3>
</div>
<div class="modal-body">
	<div class="table-responsive">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>Imagen</th>
					<th>URL</th>
					<th>Posición</th>
					<th>Eliminar</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($sliders as $slider)
				<tr>
					<td>
						<img src="{{ $slider->getPrimaryImageUrl(150,150) }}" style="max-width: 50px">
					</td>
					<td>
						<p>{{$slider->url}}</p>
					</td>
					<td>{{$slider->orderx}}</td>
					<td>
						<a href='{{ url('admin/sliders/delete',[$slider->id]) }}' class='ajax-link show-warning btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>
					</td>
				</tr>
			@endforeach			
			</tbody>
		</table>
	</div>
	<form 
		data-reload-modal-on-success='true' 
		class='ajax-submit validate-form' 
		method='post' 
		action='{{ url('admin/sliders/upload',[$section]) }}'>
			{{ csrf_field() }}
			<div class="form-group">
		        <label>Agregar imagen</label>
		        <div class="from-group">
		        	<label>URL</label>
		        	<input type="text" name="url" class="form-control" required>
		        </div>
		        <div class="from-group">
		        	<label>Alt Text</label>
		        	<input type="text" name="altText" class="form-control" required>
		        </div>
		        <div class="from-group">
		        	<label>ORDEN</label>
		        	<input type="number" name="orderx" class="form-control" required>
		        </div>
		        <div class="from-group">
		        	<label>Imagen</label>
		        	<input data-container='#galeryPreview' class='imgField' type="file" name="image" accept=".jpg,.png,.jpeg" required>
		        </div>
			</div>
			<button type='submit' class='btn btn-primary'>Subir</button>
	</form>
	<div class="images-previews images-1" id='galeryPreview'></div>
</div>
<div class="modal-footer text-left">
	<div class="text-left">
		<button type='button' data-dismiss='modal' class='btn btn-default'>Cerrar</button>
	</div>
</div>