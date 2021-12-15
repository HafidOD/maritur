@php
	$style = isset($style)?$style:'p';
	$classStyles = [
		'p'=>[
			'col-xs-6 col-md-12',
			'col-xs-6 col-md-6',
			'col-xs-6 col-md-3',
			'col-xs-6 col-md-3',
			'col-xs-12',
		],
		'l'=>[
			'col-xs-6 col-md-5',
			'col-xs-6 col-md-3',
			'col-xs-6 col-md-1',
			'col-xs-6 col-md-1',
			'col-xs-12 col-md-2',
		],
	];
	$iaux=0;
@endphp
<form id='houses-form' class="style{{$style}}">
<input type="hidden" name="search" value="1">
<div class="row">
	<div class="{{$classStyles[$style][$iaux++]}}">
		<label class="label-control">House / Destination</label>
		<input type="text" class="destination-field form-control" value="" required data-url='/vacational-rentals/autocomplete' />
	</div>
	<div class="{{$classStyles[$style][$iaux++]}}">
		<label class="label-control">Arrival - Departure</label>
		<span class="form-control input-daterange"></span>
		<input type="hidden" name="arrival" value=""/>
		<input type="hidden" name="departure" value="" />
	</div>
	<div class="{{$classStyles[$style][$iaux++]}}">
		<label class="label-control">Adults</label>
		<select name='adults' class="form-control" required >
			@for ($i = 1; $i <=10; $i++)
				<option value="{{$i}}" >{{$i}}</option>
			@endfor
		</select>
	</div>
	<div class="{{$classStyles[$style][$iaux++]}}">
		<label class="label-control">Children</label>
		<select name='children' class="form-control" required >
			@for ($i = 0; $i <=10; $i++)
				<option value="{{$i}}" >{{$i}}</option>
			@endfor
		</select>
	</div>
	<div class="{{$classStyles[$style][$iaux++]}} {{$style=='p'?'text-right':'text-center'}}">
		<button type="submit" class="btn btn-success">GO</button>
	</div>
</div>
</form>