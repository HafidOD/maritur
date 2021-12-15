<div class="row">
	<div class="col-sm-6">
		<label>Latitud</label>
		<input type="text" name="model[latitude]" class="form-control" value="{{$tour->latitude}}">
	</div>
	<div class="col-sm-6">
		<label>Longitud</label>
		<input type="text" name="model[longitude]" class="form-control" value="{{$tour->longitude}}">
	</div>
</div>
<div class="form-group">
	<label>Duración</label>
	<input type="text" name="model[duration]" class="form-control" value="{{$tour->duration}}">
</div>
<div class="form-group">
	<label>Descripcion</label>
	<textarea class="form-control html-content" name='model[description]'>{{$tour->description}}</textarea>
</div>
<div class="form-group">
	<label>Incluye</label>
	<textarea class="form-control html-content" name='model[inclusions]'>{{$tour->inclusions}}</textarea>
</div>
<div class="form-group">
	<label>No incluye</label>
	<textarea class="form-control html-content" name='model[exclusions]'>{{$tour->exclusions}}</textarea>
</div>
<div class="form-group">
	<label>Regulaciones</label>
	<textarea class="form-control html-content" name='model[regulations]'>{{$tour->regulations}}</textarea>
</div>
<div class="form-group">
	<label>Recomendaciones</label>
	<textarea class="form-control html-content" name='model[recommendations]'>{{$tour->recommendations}}</textarea>
</div>
<div class="form-group">
	<label>Políticas</label>
	<textarea class="form-control html-content" name='model[policies]'>{{$tour->policies}}</textarea>
</div>
<div class="form-group">
	<label>Itinerario</label>
	<textarea class="form-control html-content" name='model[itinerary]'>{{$tour->itinerary}}</textarea>
</div>
<div class="form-group">
	<label>Dirección</label>
	<textarea class="form-control html-content" name='model[address]'>{{$tour->address}}</textarea>
</div>
