	<input type="hidden" name="model[tourPriceId]" value="{{$model->tourPriceId}}">
	<div class="form-group">
		<label>Ciudad origen</label>
		<select class="form-control" name="model[destinationId]">
			@foreach ($destinations as $dest)
				<option {{$dest->id==$model->destinationId?'selected':''}} value="{{$dest->id}}">{{$dest->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="row">
		<div class="form-group col-md-6">
			<label>Precio adulto</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" class="number form-control required" name="model[adultPrice]" value="{{$model->adultPrice}}">
				<span class="input-group-addon">USD</span>
			</div>
		</div>
		<div class="form-group col-md-6">
			<label>Precio Niño</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" class="number form-control required" name="model[childrenPrice]" value="{{$model->childrenPrice}}">
				<span class="input-group-addon">USD</span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Descripción adicional</label>
		<textarea class="form-control html-content" name='model[description]'>{{$model->description}}</textarea>
	</div>