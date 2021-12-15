@php
	$actionField = isset($actionField)?$actionField:'#';
	$destName = isset($destName)?$destName:'';
	$arrival = isset($arrival)?$arrival:date('Y-m-d',strtotime('+1 day'));
	$pax = isset($pax)?$pax:1;
	$style = isset($style)?$style:'p';
	$classStyles = [
		'p'=>[
			'col-xs-12',
			'col-xs-12 col-sm-6',
			'col-xs-12 col-sm-6',
			'col-xs-12',
		],
		'l'=>[
			'col-xs-6 col-sm-5',
			'col-xs-6 col-sm-3',
			'col-xs-6 col-sm-2',
			'col-xs-6 col-sm-2',
		],
	];
	$iaux=0;
@endphp
<form id='transfers-form' class="style{{$style}}" method='get' action="{{$actionField}}">
	<input type="hidden" name="search" value="1">
	<div class="row">
		<div class="{{$classStyles[$style][$iaux++]}}">
			<label class="label-control">Destination</label>
			<input type="text" class="destination-field form-control" data-url='/transfers/autocomplete' value="{{$destName}}" required/>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}}">
			<label class="label-control">Arrival</label>
			<span class="input-datepicker form-control"></span>
			<input type="hidden" name="arrival" value="{{$arrival}}" />
		</div>
	 	<div class="{{$classStyles[$style][$iaux++]}}" >
			<label class="label-control">Paxes</label>
			<select class="form-control" name='pax' required >
				@for ($i = 1; $i <=10; $i++)
					<option {{$pax==$i?'selected':''}} value="{{$i}}" >{{$i}} {{$i>1?'Paxes':'Pax'}}</option>
				@endfor
			</select>
		</div>
		<div class="{{$classStyles[$style][$iaux++]}} text-right">
			<button type="submit" class="btn btn-success">GO</button>
		</div>
	</div>
</form>
