	<input type="hidden" name="model[tourId]" value="{{$tourPrice->tourId}}">
	<div class="form-group">
		<label>Nombre</label>
		<input type="text" class="form-control required" name="model[name]" value="{{$tourPrice->name}}">
	</div>
	<h4>Precios publicos</h4>
	<div class="row">
		<div class="form-group col-md-6">
			<label>Precio adulto</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" class="number form-control required" name="model[adultPrice]" value="{{$tourPrice->adultPrice}}">
				<span class="input-group-addon">USD</span>
			</div>
		</div>
		<div class="form-group col-md-6">
			<label>Precio Niño</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" class="number form-control required" name="model[childrenPrice]" value="{{$tourPrice->childrenPrice}}">
				<span class="input-group-addon">USD</span>
			</div>
		</div>
	</div>
	<h4>Precios netos (para afiliados)</h4>
	<div class="row">
		<div class="form-group col-md-6">
			<label>Precio adulto</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" class="number form-control required" name="model[netAdultPrice]" value="{{$tourPrice->netAdultPrice}}">
				<span class="input-group-addon">USD</span>
			</div>
		</div>
		<div class="form-group col-md-6">
			<label>Precio Niño</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" class="number form-control required" name="model[netChildrenPrice]" value="{{$tourPrice->netChildrenPrice}}">
				<span class="input-group-addon">USD</span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Disponibilidad en la semana</label><br>
		@php
			$validDays = $tourPrice->getWeekDaysArray();
		@endphp
		@foreach (['D','L','M','M','J','V','S'] as $key => $value)
			<label>
				{{$value}}<br>
				<input {{in_array($key,$validDays)?'checked':''}} type="checkbox" name="model[weekDays][]" value="{{$key}}">
			</label>
		@endforeach
	</div>
	<div class="form-group">
		<label>Descripción adicional</label>
		<textarea class="form-control html-content" name='model[description]'>{{$tourPrice->description}}</textarea>
	</div>
	<button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> Guardar</button>
	